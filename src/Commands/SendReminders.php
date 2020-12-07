<?php

namespace LaravelEnso\Calendar\Commands;

use Illuminate\Console\Command;
use LaravelEnso\Calendar\Models\Reminder;

class SendReminders extends Command
{
    protected $signature = 'enso:calendar:send-reminders';

    protected $description = 'Send calendar reminders';

    public function handle()
    {
        Reminder::with('createdBy')->shouldSend()
            ->get()->each->send();
    }
}
