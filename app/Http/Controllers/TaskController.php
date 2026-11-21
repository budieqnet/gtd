<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\Employee;
use App\Mail\TaskAssignedMail;
use App\Mail\TaskStatusUpdatedMail;
use App\Notifications\TaskAssigned as TaskAssignedNotification;
use App\Notifications\TaskStatusUpdated as TaskStatusUpdatedNotification;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.performance');
        }

        $subordinates = $user->allSubordinates();
        $subordinateIds = $subordinates->pluck('id')->toArray();

        $employeeForCreate = $subordinates;

        $viewingEmployeeId = null;
        if ($request->filled('employee_id')) {
            $targetId = (int) $request->input('employee_id');
            if ($targetId === $user->id || in_array($targetId, $subordinateIds, true)) {
                $viewingEmployeeId = $targetId;
            }
        }

        $query = Task::with(['assignedTo', 'category', 'createdBy']);

        if ($viewingEmployeeId) {
            $query->where('assigned_to', $viewingEmployeeId);
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id);
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();

        $stats = [
            'total' => $tasks->count(),
            'done' => $tasks->where('status', 'Done')->count(),
            'pending' => $tasks->whereIn('status', ['Inbox', 'In Progress'])->count(),
            'overdue' => $tasks->where('status', '!=', 'Done')->filter(fn($t) => $t->due_date && $t->due_date < now())->count(),
            'rate' => $tasks->count() > 0 ? round(($tasks->where('status', 'Done')->count() / $tasks->count()) * 100) : 0,
        ];

        $categories = Category::all();

        return view('tasks.index', compact('tasks', 'stats', 'categories', 'employeeForCreate', 'viewingEmployeeId', 'subordinates'));
    }

    public function show(Task $task)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.performance');
        }

        if ($task->assigned_to != $user->id && $task->created_by != $user->id) {
            return back()->with('error', 'Kamu tidak punya akses untuk melihat tugas ini.');
        }

        $task->load(['assignedTo', 'category', 'createdBy']);

        $categories = Category::all();

        return view('tasks.show', compact('task', 'categories'));
    }

    public function history(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.performance');
        }

        $subordinates = $user->allSubordinates();
        $subordinateIds = $subordinates->pluck('id')->toArray();

        $viewingEmployeeId = null;
        if ($request->filled('employee_id')) {
            $targetId = (int) $request->input('employee_id');
            if ($targetId === $user->id || in_array($targetId, $subordinateIds, true)) {
                $viewingEmployeeId = $targetId;
            }
        }

        $month = null;
        if ($request->filled('month')) {
            $monthInput = $request->input('month');
            if (is_string($monthInput) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $monthInput)) {
                $month = \Carbon\Carbon::createFromFormat('!Y-m', $monthInput)->startOfMonth();
            }
        }

        $query = Task::with(['assignedTo', 'category', 'createdBy'])
            ->where('status', 'Done');

        if ($viewingEmployeeId) {
            $query->where('assigned_to', $viewingEmployeeId);
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id);
            });
        }

        if ($month) {
            $query->whereBetween('updated_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()]);
        }

        $tasks = $query->orderBy('updated_at', 'desc')->paginate(20);

        // Data for month navigation
        $monthNames = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $monthValue = $month ? $month->format('Y-m') : now()->format('Y-m');
        $monthLabel = $month ? $monthNames[(int) $month->format('n')] . ' ' . $month->format('Y') : $monthNames[(int) now()->format('n')] . ' ' . now()->format('Y');
        $prevMonth = $month ? $month->copy()->subMonthNoOverflow()->format('Y-m') : now()->subMonthNoOverflow()->format('Y-m');
        $nextMonth = $month ? $month->copy()->addMonthNoOverflow()->format('Y-m') : now()->addMonthNoOverflow()->format('Y-m');

        return view('tasks.history', compact('tasks', 'subordinates', 'viewingEmployeeId', 'monthValue', 'monthLabel', 'prevMonth', 'nextMonth'));
    }

    public function create()
    {
        $user = Auth::user();
        $employeeList = $user->allSubordinates();
        return view('tasks.create-form', compact('employeeList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:Low,Medium,High',
            'status' => 'required|in:Inbox,In Progress,Done',
            'due_date' => 'nullable|date',
             'assigned_to' => ['nullable', 'integer', 'exists:employees,id'],
        ]);

        if ($request->filled('assigned_to') && (int)$request->assigned_to !== Auth::id()) {
            $subordinates = $user->allSubordinates();
            $subordinateIds = $subordinates->pluck('id')->toArray();
            if (!in_array((int)$request->input('assigned_to'), $subordinateIds)) {
                return back()
                    ->withInput()
                    ->withErrors(['assigned_to' => 'Hanya bisa assign tugas ke bawahan Anda.']);
            }
            $validated['assigned_to'] = (int)$request->assigned_to;
        } else {
            $validated['assigned_to'] = Auth::id();
        }

        $validated['created_by'] = Auth::id();

        $task = Task::create($validated);

        if ($task->assigned_to && $task->assigned_to !== Auth::id()) {
            $assignee = Employee::find($task->assigned_to);
            if ($assignee && $assignee->email) {
                Mail::to($assignee->email)->send(new TaskAssignedMail([
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description ?? '',
                    'priority' => $task->priority,
                    'due_date' => $task->due_date,
                ], $user->name));
            }
            if ($assignee) {
                $assignee->notify(new TaskAssignedNotification($task, $user->name));
            }
            if ($assignee && $assignee->phone) {
                $wa = new WhatsAppService;
                $wa->send($assignee->phone, 'Tugas Baru: ' . $task->title . "\nPrioritas: " . $task->priority . ($task->due_date ? "\nDeadline: " . $task->due_date->format('d/m/Y') : '') . "\nDari: " . $user->name);
            }
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas berhasil dibuat.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $user = Auth::user();

        if ($task->assigned_to != $user->id && $task->created_by != $user->id) {
            return back()->with('error', 'Kamu tidak punya akses untuk update tugas ini.');
        }

        if ($task->status === 'Done') {
            return back()->with('error', 'Tugas yang sudah selesai (Done) terkunci dan tidak bisa diubah lagi.');
        }

        $validated = $request->validate([
            'status' => 'required|in:Inbox,In Progress,Done',
        ]);

        $oldStatus = $task->status;

        if ($validated['status'] === 'Done' && $task->assigned_to === $user->id && $task->created_by !== $user->id) {
            return back()->with('error', 'Hanya atasan yang bisa mengubah status menjadi Done.');
        }

        $task->update($validated);

        if ($task->assigned_to === $user->id && $task->created_by !== $user->id) {
            $creator = \App\Models\Employee::find($task->created_by);
            if ($creator) {
                $creator->notify(new TaskStatusUpdatedNotification($task, $oldStatus, $task->status, $user->name));
                if ($creator->email) {
                    Mail::to($creator->email)->send(new TaskStatusUpdatedMail([
                        'id' => $task->id,
                        'title' => $task->title,
                    ], $oldStatus, $task->status, $user->name));
                }
                if ($creator->phone) {
                    $wa = new WhatsAppService;
                    $wa->send($creator->phone, 'Status Tugas Diperbarui: ' . $task->title . "\n" . $oldStatus . ' → ' . $task->status . "\nOleh: " . $user->name);
                }
            }
        }

        return back()->with('success', 'Status tugas berhasil diperbarui!');
    }

    public function updateTask(Request $request, Task $task)
    {
        $user = Auth::id();

        if ($task->created_by != $user) {
            return back()->with('error', 'Kamu tidak punya akses untuk edit tugas ini.');
        }

        if ($task->status === 'Done') {
            return back()->with('error', 'Tugas yang sudah selesai (Done) terkunci dan tidak bisa diedit lagi.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => ['required', 'exists:categories,id'],
            'priority' => 'required|in:Low,Medium,High',
            'status' => 'required|in:Inbox,In Progress,Done',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return back()->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Task $task)
    {
        $user = Auth::id();

        if ($task->created_by != $user) {
            return back()->with('error', 'Kamu tidak punya akses untuk hapus tugas ini.');
        }

        if ($task->status === 'Done') {
            return back()->with('error', 'Tugas yang sudah selesai (Done) terkunci dan tidak bisa dihapus.');
        }

        $task->delete();

        return back()->with('success', 'Tugas berhasil dihapus!');
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.performance');
        }

        $subordinates = $user->allSubordinates();

        if ($subordinates->isEmpty()) {
            return redirect()->route('tasks.index')
                ->with('info', 'Anda belum memiliki anggota tim.');
        }

        $subordinateIds = $subordinates->pluck('id')->toArray();

        // Periode bulan berjalan (bisa diganti lewat parameter ?month=YYYY-MM)
        $monthInput = $request->input('month');
        if (is_string($monthInput) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $monthInput)) {
            $month = \Carbon\Carbon::createFromFormat('!Y-m', $monthInput)->startOfMonth();
        } else {
            $month = now()->startOfMonth();
        }

        $allTasks = Task::whereIn('assigned_to', $subordinateIds)
            ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->with(['assignedTo', 'category', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Data periode untuk tampilan & navigasi
        $monthNames = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $monthValue = $month->format('Y-m');
        $monthLabel = $monthNames[(int) $month->format('n')] . ' ' . $month->format('Y');
        $prevMonth = $month->copy()->subMonthNoOverflow()->format('Y-m');
        $nextMonth = $month->copy()->addMonthNoOverflow()->format('Y-m');

        $categories = Category::all();

        // Statistik tugas per subordinate dalam satu query (hindari N+1)
        $stats = Task::whereIn('assigned_to', $subordinateIds)
            ->selectRaw('assigned_to')
            ->selectRaw("SUM(CASE WHEN status IN ('Inbox', 'In Progress') THEN 1 ELSE 0 END) as pending")
            ->selectRaw("SUM(CASE WHEN status = 'Done' THEN 1 ELSE 0 END) as done")
            ->groupBy('assigned_to')
            ->get()
            ->keyBy('assigned_to');

        // Map nama subordinate dengan statistiknya
        $taskStats = [];
        foreach ($subordinates as $sub) {
            $row = $stats[$sub->id] ?? null;
            $taskStats[$sub->name] = [
                'pending' => (int)($row ? $row->pending : 0),
                'done' => (int)($row ? $row->done : 0),
            ];
        }

        return view('tasks.dashboard-atasan', compact('allTasks', 'taskStats', 'categories', 'monthValue', 'monthLabel', 'prevMonth', 'nextMonth'));
    }
}
