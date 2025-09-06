<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Mail\TaskReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'إرسال تذكيرات المهام عبر الإيميل حسب التكرار';

    public function handle()
    {
        $today = Carbon::today();

        $tasks = Task::with('user')->get();

        foreach ($tasks as $task) {
            if (!$task->user || !$task->user->email) {
                continue;
            }

            // شرط التكرار
            if ($this->shouldSendReminder($task, $today)) {
                Mail::to($task->user->email)->send(new TaskReminderMail($task));
                $this->info("تم إرسال التذكير للمهمة ID {$task->id}");
            }
        }
    }

    private function shouldSendReminder($task, $today)
    {
        $taskDate = Carbon::parse($task->date);

        switch ($task->repeat_interval) {
        case 'يومي':
            $shouldSend = true;
            break;

        case 'أسبوعي':
            $shouldSend = $task->date->dayOfWeek === Carbon::today()->dayOfWeek;
            break;

        case 'شهري':
            $shouldSend = $task->date->day === Carbon::today()->day;
            break;

        default:
            $shouldSend = $task->date->isSameDay(Carbon::today());
    }

    }
}
