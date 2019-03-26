<?php

use LaravelEnso\StructureManager\app\Classes\StructureMigration;

class CreateStructureForReminders extends StructureMigration
{
    protected $permissions = [
        ['name' => 'core.calendar.reminders.store', 'description' => 'Delete event', 'type' => 1, 'is_default' => false],
        ['name' => 'core.calendar.reminders.update', 'description' => 'Delete event', 'type' => 1, 'is_default' => false],
        ['name' => 'core.calendar.reminders.destroy', 'description' => 'Delete event', 'type' => 1, 'is_default' => false],
    ];
}
