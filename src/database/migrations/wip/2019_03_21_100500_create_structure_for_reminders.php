<?php

use LaravelEnso\Migrator\app\Database\Migration;

class CreateStructureForReminders extends Migration
{
    protected $permissions = [
        ['name' => 'core.calendar.reminders.store', 'description' => 'Delete event', 'type' => 1, 'is_default' => false],
        ['name' => 'core.calendar.reminders.update', 'description' => 'Delete event', 'type' => 1, 'is_default' => false],
        ['name' => 'core.calendar.reminders.destroy', 'description' => 'Delete event', 'type' => 1, 'is_default' => false],
    ];
}
