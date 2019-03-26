<?php

use LaravelEnso\StructureManager\app\Classes\StructureMigration;

class CreateStructureForCalendar extends StructureMigration
{
    protected $menu = [
        'name' => 'Calendar', 'icon' => 'calendar-alt', 'route' => 'core.calendar.events.index', 'order_index' => 200, 'has_children' => false,
    ];
}
