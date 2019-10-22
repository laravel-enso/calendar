<?php

namespace LaravelEnso\Calendar\app\Services;

use LaravelEnso\Calendar\app\Models\Calendar;

class Calendars
{
    private $calendars;

    public function __construct()
    {
        $this->initCalendars();
    }

    public function register($calendars)
    {
        collect($calendars)
            ->map(function ($calendar) {
                return is_string($calendar) ? new $calendar() : $calendar;
            })->each(function ($calendar) {
                return $this->calendars->put($calendar->getKey(), $calendar);
            });
    }

    public function remove($aliases)
    {
        $this->calendars->forget(collect($aliases));
    }

    public function all()
    {
        return $this->calendars;
    }

    private function initCalendars()
    {
        $this->calendars = collect();

        if (! app()->runningUnitTests()) {
            $this->register(Calendar::get());
        }
    }
}
