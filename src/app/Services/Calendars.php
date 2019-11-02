<?php

namespace LaravelEnso\Calendar\app\Services;

use Illuminate\Support\Facades\Auth;
use LaravelEnso\Calendar\app\Models\Calendar;
use LaravelEnso\Calendar\app\Contracts\Calendar as Contract;

class Calendars
{
    private $calendars;
    private $ready;

    public function __construct()
    {
        $this->calendars = collect();
    }

    public function all()
    {
        if (! $this->ready) {
            $this->register(Calendar::get());
            $this->ready = true;
        }

        return Auth::user()->isAdmin() || Auth::user()->isSupervisor()
            ? $this->calendars
            : $this->calendars->filter(function($calendar) {
                return ! $calendar->private()
                    || $calendar instanceof Calendar
                        && Auth::user()->id === $calendar->created_by;
            });
    }

    public function only(array $calendars)
    {
        return $this->all()->filter(function ($calendar) use ($calendars) {
            return in_array($calendar->getKey(), $calendars);
        });
    }

    public function keys()
    {
        return $this->all()->map(function ($calendar) {
            return $calendar->getKey();
        });
    }

    public function register($calendars)
    {
        collect($calendars)
            ->map(function ($calendar) {
                return is_string($calendar) ? new $calendar() : $calendar;
            })->reject(function ($calendar) {
                return $this->registered($calendar);
            })->each(function (Contract $calendar) {
                $this->calendars->push($calendar);
            });
    }

    public function remove($aliases)
    {
        $this->calendars->forget(collect($aliases));
    }

    private function registered($calendar)
    {
        return $this->calendars->contains(function ($existing) use ($calendar) {
            return $existing->getKey() === $calendar->getKey();
        });
    }
}
