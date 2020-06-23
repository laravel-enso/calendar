<?php

namespace LaravelEnso\Calendar\Facades;

use Illuminate\Support\Facades\Facade;

class Calendars extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'calendars';
    }
}
