<?php

namespace LaravelEnso\Calendar\app\Commands;

use Illuminate\Console\Command;
use LaravelEnso\Calendar\app\Models\Reminder;
use LaravelEnso\Calendar\app\Notifications\ReminderNotification;

class Notify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enso:calendar:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify Reminders';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reminders = Reminder::readyForNotify()->get();

        $reminders->each(function (Reminder $r) {
            $r->createdBy->notify(new ReminderNotification($r));
            $r->update(['reminded_at' => now()]);
        });
    }
}
