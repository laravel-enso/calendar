<?php

namespace LaravelEnso\Calendar\app\Enums;

use LaravelEnso\Helpers\app\Classes\Enum;

class Classes extends Enum
{
    protected static function attributes()
    {
        return config('enso.calendar.calendarClasses');
    }
}
