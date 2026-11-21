<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Task;
use App\Mail\DailyReminderMail;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDailyReminder extends Command
{
    protected $signature = 'gtd:send-reminder';
    protected $description = 'Kirim pengingat harian tugas GTD ke setiap employee';

    public function handle()
    {
        $today = Carbon::today();
        $employees = Employee::all();

        if ($employees->isEmpty()) {
            $this->warn('Tidak ada employee terdaftar.');
            return;
        }

        $sentCount = 0;

        foreach ($employees as $employee) {
            $tasks = Task::where('assigned_to', $employee->id)
                ->where('status', '!=', 'Done')
                ->where(function ($query) use ($today) {
                    $query->where('due_date', '<=', $today)
                          ->orWhereNull('due_date');
                })
                ->orderByRaw("FIELD(priority, 'High', 'Medium', 'Low')")
                ->get();

            if ($tasks->isEmpty()) {
                $this->line("{$employee->name}: tidak ada tugas mendesak, dilewati.");
                continue;
            }

            try {
                Mail::to($employee->email)->send(new DailyReminderMail($tasks, $employee->name));
                $this->info("{$employee->name} ({$employee->email}): pengingat terkirim.");

                if ($employee->phone) {
                    $wa = new WhatsAppService;
                    $taskList = $tasks->take(3)->pluck('title')->join("\n- ");
                    $wa->send($employee->phone, "☀️ Morning Reminder!\n\nTugas hari ini:\n- " . $taskList . ($tasks->count() > 3 ? "\n+ " . ($tasks->count() - 3) . " tugas lainnya" : ''));
                }

                $sentCount++;
            } catch (\Exception $e) {
                $this->error("{$employee->name}: gagal - {$e->getMessage()}");
            }
        }

        if ($sentCount === 0) {
            $this->info('Tidak ada pengingat yang dikirim.');
        } else {
            $this->info("Selesai! {$sentCount} pengingat berhasil dikirim.");
        }
    }
}
