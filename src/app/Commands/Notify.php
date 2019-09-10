<?php

namespace LaravelEnso\Calendar\app\Commands;

use Illuminate\Console\Command;
use LaravelEnso\Calendar\app\Models\Reminder;

class Notify extends Command
{
    protected $signature = 'enso:calendar:notify';

    protected $description = 'Notify Reminders';

    public function handle()
    {
        Reminder::with('createdBy')->readyForNotify()
            ->get()->each->notify();
    }
}
