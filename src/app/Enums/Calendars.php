<?php

namespace LaravelEnso\Calendar\app\Enums;

use LaravelEnso\Helpers\app\Classes\Enum;

class Calendars extends Enum
{
    protected static function attributes()
    {
        return config('enso.calendar.calendars');
    }
}
