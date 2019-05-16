<?php

use LaravelEnso\Migrator\app\Database\Migration;

class CreateStructureForCalendar extends Migration
{
    protected $menu = [
        'name' => 'Calendar', 'icon' => 'calendar-alt', 'route' => 'core.calendar.events.index', 'order_index' => 200, 'has_children' => false,
    ];
}
