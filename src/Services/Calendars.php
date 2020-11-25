<?php

namespace LaravelEnso\Calendar\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
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
        if (! $this->ready) {
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
        (new Collection($calendars))
            ->map(fn ($calendar) => is_string($calendar) ? new $calendar() : $calendar)
            ->reject(fn ($calendar) => $this->registered($calendar))
            ->filter(fn ($calendar) => $this->canAccess($calendar))
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

    private function canAccess($calendar)
    {
        $roles = Config::get('enso.calendar.roles')[get_class($calendar)] ?? null;

        return $roles === null || Collection::wrap($roles)->contains(Auth::user()->role_id);
    }
}
