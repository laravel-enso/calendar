<?php

use LaravelEnso\Migrator\App\Database\Migration;
use LaravelEnso\Permissions\App\Enums\Types;

class CreateStructureForEvents extends Migration
{
    protected $permissions = [
        ['name' => 'core.calendar.events.index', 'description' => 'Get events', 'type' => Types::Read, 'is_default' => true],
        ['name' => 'core.calendar.events.create', 'description' => 'Create a new event', 'type' => Types::Read, 'is_default' => true],
        ['name' => 'core.calendar.events.store', 'description' => 'Store a new event', 'type' => Types::Write, 'is_default' => true],
        ['name' => 'core.calendar.events.edit', 'description' => 'Edit event', 'type' => Types::Read, 'is_default' => true],
        ['name' => 'core.calendar.events.update', 'description' => 'Update event', 'type' => Types::Write, 'is_default' => true],
        ['name' => 'core.calendar.events.destroy', 'description' => 'Delete event', 'type' => Types::Write, 'is_default' => true],
    ];

    protected $parentMenu = '';
}
