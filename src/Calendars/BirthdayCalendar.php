<?php

namespace LaravelEnso\Calendar\Calendars;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use LaravelEnso\Calendar\Contracts\CustomCalendar;
use LaravelEnso\Calendar\Enums\Colors;
use LaravelEnso\People\Models\Person;

class BirthdayCalendar implements CustomCalendar
{
    private Carbon $startDate;
    private Carbon $endDate;

    public function getKey()
    {
        return 'birthday-calendar';
    }

    public function name(): string
    {
        return 'Birthdays';
    }

    public function color(): string
    {
        return Colors::Purple;
    }

    public function private(): bool
    {
        return false;
    }

    public function readonly(): bool
    {
        return true;
    }

    public function events(Carbon $startDate, Carbon $endDate): Collection
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $roles = Config::get('enso.calendar.birthdays.roles');

        return Person::query()
            ->unless($roles === ['*'], fn ($query) => $query
                ->whereHas('user', fn ($query) => $query->whereIn('role_id', $roles)))
            ->when(! $this->withinSameYear(), $this->differentYearQuery())
            ->when(
                $this->withinSameYear() && $this->withinSameMonth(),
                $this->sameMonthQuery()
            )->when(
                $this->withinSameYear() && ! $this->withinSameMonth(),
                $this->differentMonthQuery()
            )->get()
            ->map(fn ($person) => new BirthdayEvent($person, $this->year($person)));
    }

    private function sameMonthQuery()
    {
        return function ($query) {
            $query->whereMonth('birthday', '=', $this->startDate)
                ->whereDay('birthday', '>=', $this->startDate)
                ->whereDay('birthday', '<=', $this->endDate);
        };
    }

    private function differentMonthQuery()
    {
        return function ($query) {
            $query->where(function ($query) {
                $query->whereMonth('birthday', $this->startDate)
                    ->whereDay('birthday', '>=', $this->startDate);
            })->orWhere(function ($query) {
                $query->whereMonth('birthday', $this->endDate)
                    ->whereDay('birthday', '<=', $this->endDate);
            })->orWhere(function ($query) {
                $query->whereMonth('birthday', '>', $this->startDate)
                    ->whereMonth('birthday', '<', $this->endDate);
            });
        };
    }

    private function differentYearQuery()
    {
        return function ($query) {
            $query->where(function ($query) {
                $query->whereMonth('birthday', $this->startDate)
                    ->whereDay('birthday', '>=', $this->startDate);
            })->orWhere(function ($query) {
                $query->whereMonth('birthday', '>', $this->startDate);
            })->orWhere(function ($query) {
                $query->whereMonth('birthday', $this->endDate)
                    ->whereDay('birthday', '<=', $this->endDate);
            })->orWhere(function ($query) {
                $query->whereMonth('birthday', '<', $this->endDate);
            });
        };
    }

    private function year($person)
    {
        return $person->birthday->month === $this->startDate->month
            ? $this->startDate->year
            : $this->endDate->year;
    }

    private function withinSameMonth(): bool
    {
        return $this->endDate->month === $this->startDate->month;
    }

    private function withinSameYear(): bool
    {
        return $this->endDate->month >= $this->startDate->month;
    }
}
