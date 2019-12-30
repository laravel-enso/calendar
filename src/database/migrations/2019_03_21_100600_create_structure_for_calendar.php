<?php

use LaravelEnso\Migrator\App\Database\Migration;
use LaravelEnso\Permissions\App\Enums\Types;

class CreateStructureForCalendar extends Migration
{
    protected $permissions = [
        ['name' => 'core.calendar.create', 'description' => 'Create a new calendar', 'type' => Types::Read, 'is_default' => true],
        ['name' => 'core.calendar.store', 'description' => 'Store a new calendar', 'type' => Types::Write, 'is_default' => true],
        ['name' => 'core.calendar.edit', 'description' => 'Edit calendar', 'type' => Types::Read, 'is_default' => true],
        ['name' => 'core.calendar.update', 'description' => 'Update calendar', 'type' => Types::Write, 'is_default' => true],
        ['name' => 'core.calendar.destroy', 'description' => 'Delete calendar', 'type' => Types::Write, 'is_default' => true],
        ['name' => 'core.calendar.index', 'description' => 'Get calendars', 'type' => Types::Read, 'is_default' => true],
        ['name' => 'core.calendar.options', 'description' => 'Get options for select', 'type' => Types::Read, 'is_default' => true],
    ];

    protected $menu = [
        'name' => 'Calendar', 'icon' => 'calendar-alt', 'route' => 'core.calendar.index', 'order_index' => 200, 'has_children' => false,
    ];
}
