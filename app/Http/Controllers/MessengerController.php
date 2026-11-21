<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Employee;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Mail\NewMessageMail;
use App\Notifications\NewMessage as NewMessageNotification;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessengerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employees = $this->employeeList($user);
        return view('messenger.index', compact('employees'));
    }

    public function employees()
    {
        return response()->json($this->employeeList(Auth::user()));
    }

    /**
     * Daftar semua pegawai (kecuali diri sendiri) beserta status online.
     */
    private function employeeList(Employee $user)
    {
        $onlineIds = $this->onlineEmployeeIds();

        return Employee::where('employees.id', '!=', $user->id)
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->orderBy('positions.level')
            ->orderBy('positions.sort_order')
            ->orderBy('employees.name')
            ->select('employees.*')
            ->get()
            ->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'nip' => $e->nip,
                'is_online' => in_array($e->id, $onlineIds, true),
            ])
            ->values();
    }

    /**
     * ID pegawai yang sedang online (punya session guard "web" aktif dalam 5 menit terakhir).
     * Payload session di-decode karena disimpan ter-base64 oleh Laravel.
     */
    private function onlineEmployeeIds(): array
    {
        $threshold = now()->subMinutes(5)->getTimestamp();

        return DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', $threshold)
            ->get(['user_id', 'payload'])
            ->filter(fn($s) => str_contains(base64_decode($s->payload, true) ?: '', 'login_web_'))
            ->pluck('user_id')
            ->unique()
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }

    public function conversations()
    {
        $user = Auth::user();
        $conversations = Conversation::whereHas('participants', function ($q) use ($user) {
            $q->where('employee_id', $user->id);
        })->with(['participants.employee', 'lastMessage.sender'])->latest('updated_at')->get();

        $result = $conversations->map(function ($conv) use ($user) {
            $other = $conv->participants->firstWhere('employee_id', '!=', $user->id)?->employee;
            $participant = $conv->participants->firstWhere('employee_id', $user->id);
            $unread = Message::where('conversation_id', $conv->id)
                ->where('sender_id', '!=', $user->id)
                ->where('created_at', '>', $participant?->last_read_at ?? now()->subYears(100))
                ->count();

            return [
                'id' => $conv->id,
                'name' => $conv->name ?? $other?->name ?? 'Unknown',
                'photo' => $other?->photo ? Storage::disk('public')->url($other->photo) : null,
                'other_id' => $other?->id,
                'last_message' => $conv->lastMessage?->message,
                'last_time' => $conv->lastMessage?->created_at?->diffForHumans(),
                'unread' => $unread,
            ];
        });

        return response()->json($result);
    }

    public function messages(Conversation $conversation)
    {
        $user = Auth::user();
        $isParticipant = $conversation->participants()->where('employee_id', $user->id)->exists();
        if (!$isParticipant) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('employee_id', $user->id)
            ->update(['last_read_at' => now()]);

        $messages = $conversation->messages()->with(['sender', 'attachments'])->oldest()->get()->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'sender_name' => $msg->sender?->name,
                'message' => $msg->message,
                'attachments' => $msg->attachments->map(function ($att) {
                    return [
                        'id' => $att->id,
                        'url' => Storage::disk('public')->url($att->file_path),
                        'name' => $att->file_name,
                        'type' => $att->file_type,
                        'size' => $att->file_size,
                    ];
                }),
                'created_at' => $msg->created_at->format('H:i'),
                'is_mine' => $msg->sender_id === Auth::id(),
            ];
        });

        return response()->json($messages);
    }

    public function send(Request $request, Conversation $conversation)
    {
        $user = Auth::user();
        $isParticipant = $conversation->participants()->where('employee_id', $user->id)->exists();
        if (!$isParticipant) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $request->validate([
            'message' => 'nullable|string|max:10000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:51200',
        ]);

        if (!$request->filled('message') && !$request->hasFile('attachments')) {
            return response()->json(['error' => 'Pesan atau file diperlukan'], 422);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $request->input('message'),
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('messenger/' . $conversation->id, 'public');
                MessageAttachment::create([
                    'message_id' => $message->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        $conversation->touch();

        $otherParticipant = $conversation->participants()->where('employee_id', '!=', $user->id)->first();
        $recipient = $otherParticipant?->employee;

        if ($recipient) {
            $messageText = (string) $request->input('message', '');
            $preview = $messageText !== '' ? Str::limit($messageText, 100) : '(Mengirim lampiran)';

            // Notifikasi lonceng (database) langsung tersimpan
            $recipient->notify(new NewMessageNotification($conversation->id, $user->id, $user->name, $preview));

            // Email & WhatsApp dijalankan setelah response terkirim, agar pengiriman
            // chat tidak menunggu dan kegagalan kirim tidak membuat error 500
            $recipientEmail = $recipient->email;
            $recipientPhone = $recipient->phone;
            $senderName = $user->name;
            $conversationId = $conversation->id;

            dispatch(function () use ($recipientEmail, $recipientPhone, $senderName, $messageText, $conversationId) {
                if ($recipientEmail) {
                    try {
                        Mail::to($recipientEmail)->send(new NewMessageMail($senderName, $messageText, $conversationId));
                    } catch (\Throwable $e) {
                        Log::error('[MESSENGER] Gagal mengirim email pesan baru: ' . $e->getMessage());
                    }
                }

                if ($recipientPhone) {
                    try {
                        $wa = new WhatsAppService;
                        $wa->send($recipientPhone, 'Pesan baru dari ' . $senderName . ': ' . ($messageText !== '' ? $messageText : '(Mengirim file/lampiran)'));
                    } catch (\Throwable $e) {
                        Log::error('[MESSENGER] Gagal mengirim WhatsApp: ' . $e->getMessage());
                    }
                }
            })->afterResponse();
        }

        return response()->json(['success' => true, 'message_id' => $message->id]);
    }

    public function start(Request $request)
    {
        $user = Auth::user();
        $request->validate(['employee_id' => 'required|exists:employees,id']);

        $otherId = (int) $request->employee_id;
        if ($otherId === $user->id) {
            return response()->json(['error' => 'Tidak bisa chat dengan diri sendiri'], 422);
        }

        $existing = Conversation::whereHas('participants', function ($q) use ($user, $otherId) {
            $q->where('employee_id', $user->id);
        })->whereHas('participants', function ($q) use ($otherId) {
            $q->where('employee_id', $otherId);
        })->where('type', 'individual')->first();

        if ($existing) {
            return response()->json(['conversation_id' => $existing->id]);
        }

        $conversation = Conversation::create(['type' => 'individual']);
        ConversationParticipant::create(['conversation_id' => $conversation->id, 'employee_id' => $user->id]);
        ConversationParticipant::create(['conversation_id' => $conversation->id, 'employee_id' => $otherId]);

        return response()->json(['conversation_id' => $conversation->id]);
    }

    public function unreadCount()
    {
        $user = Auth::user();
        $count = 0;
        $participants = ConversationParticipant::where('employee_id', $user->id)->get();
        foreach ($participants as $p) {
            $count += Message::where('conversation_id', $p->conversation_id)
                ->where('sender_id', '!=', $user->id)
                ->where('created_at', '>', $p->last_read_at ?? now()->subYears(100))
                ->count();
        }
        return response()->json(['count' => $count]);
    }
}
