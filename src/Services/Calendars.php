<?php

namespace LaravelEnso\Calendar\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use LaravelEnso\Calendar\Calendars\BirthdayCalendar;
use LaravelEnso\Calendar\Contracts\Calendar as Contract;
use LaravelEnso\Calendar\Models\Calendar;

class Calendars
{
    private Collection $calendars;
    private bool $ready;

    public function __construct()
    {
        $this->ready = false;
        $this->calendars = new Collection();
    }

    public function all()
    {
        if (!$this->ready) {
            $this->register(Calendar::get());
            $this->register(BirthdayCalendar::class);
            $this->ready = true;
        }

        return Auth::user()->isAdmin() || Auth::user()->isSupervisor()
            ? $this->calendars
            : $this->calendars
            ->filter(fn ($calendar) => Auth::user()->can('access', $calendar));
    }

    public function only(array $calendars)
    {
        return $this->all()
            ->filter(fn ($calendar) => in_array($calendar->getKey(), $calendars));
    }

    public function keys()
    {
        return $this->all()->map(fn ($calendar) => $calendar->getKey());
    }

    public function register($calendars)
    {
        Collection::wrap($calendars)
            ->map(fn ($calendar) => is_string($calendar) ? new $calendar() : $calendar)
            ->reject(fn ($calendar) => $this->registered($calendar))
            ->each(fn (Contract $calendar) => $this->calendars->push($calendar));
    }

    public function remove($aliases)
    {
        $this->calendars->forget($aliases);
    }

    private function registered($calendar)
    {
        return $this->calendars
            ->contains(fn ($existing) => ($existing->getKey() === $calendar->getKey()));
    }
}
