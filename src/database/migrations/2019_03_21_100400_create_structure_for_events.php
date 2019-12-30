<?php

use LaravelEnso\Migrator\App\Database\Migration;

class CreateStructureForEvents extends Migration
{
    protected $permissions = [
        ['name' => 'core.calendar.events.index', 'description' => 'Get events', 'type' => 0, 'is_default' => true],
        ['name' => 'core.calendar.events.create', 'description' => 'Create a new event', 'type' => 0, 'is_default' => true],
        ['name' => 'core.calendar.events.store', 'description' => 'Store a new event', 'type' => 1, 'is_default' => true],
        ['name' => 'core.calendar.events.edit', 'description' => 'Edit event', 'type' => 0, 'is_default' => true],
        ['name' => 'core.calendar.events.update', 'description' => 'Update event', 'type' => 1, 'is_default' => true],
        ['name' => 'core.calendar.events.destroy', 'description' => 'Delete event', 'type' => 1, 'is_default' => true],
    ];

    protected $parentMenu = '';
}
