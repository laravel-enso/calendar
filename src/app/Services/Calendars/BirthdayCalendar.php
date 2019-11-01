<?php

namespace LaravelEnso\Calendar\app\Services\Calendars;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use LaravelEnso\Calendar\app\Contracts\CustomCalendar;
use LaravelEnso\Calendar\app\Enums\Colors;
use LaravelEnso\People\app\Models\Person;

class BirthdayCalendar implements CustomCalendar
{
    private $startDate;
    private $endDate;

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

        return Person::query()
            ->when($this->withinSameYear(), $this->sameYearQuery())
            ->when(! $this->withinSameYear(), $this->differentYearQuery())
            ->get()
            ->map(function ($person) {
                return new PersonBirthdayEvent($person, $this->year($person));
            });
    }

    private function withinSameYear(): bool
    {
        return $this->endDate->month >= $this->startDate->month;
    }

    private function sameYearQuery()
    {
        return function ($query) {
            $query
                ->whereMonth('birthday', '>=', $this->startDate)
                ->whereDay('birthday', '>=', $this->startDate)
                ->whereMonth('birthday', '<=', $this->endDate)
                ->whereDay('birthday', '<=', $this->endDate);
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
}
