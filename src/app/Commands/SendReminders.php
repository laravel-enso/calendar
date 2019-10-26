<?php

namespace LaravelEnso\Calendar\app\Commands;

use Illuminate\Console\Command;
use LaravelEnso\Calendar\app\Models\Reminder;

class SendReminders extends Command
{
    protected $signature = 'enso:calendar:send-reminders';

    protected $description = 'Send calendar reminders';

    public function handle()
    {
        Reminder::with('createdBy')->readyToNotify()
            ->get()->each->send();
    }
}
