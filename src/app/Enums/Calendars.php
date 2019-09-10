<?php

namespace LaravelEnso\Calendar\app\Enums;

use LaravelEnso\Enums\app\Services\Enum;

class Calendars extends Enum
{
    protected static function attributes()
    {
        return config('enso.calendar.calendars');
    }
}
