<?php

namespace LaravelEnso\Calendar\app\Services\Calendars;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use LaravelEnso\Calendar\app\Contracts\CustomCalendar;
use LaravelEnso\Calendar\app\Enums\Colors;
use LaravelEnso\People\app\Models\Person;

class BirthdayCalendar implements CustomCalendar
{
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
        $year = $endDate->format('Y');
        $start = $startDate->format('Y-m-d');
        $end = $endDate->format('Y-m-d');

        return Person::query()
            ->whereRaw("STR_TO_DATE(DATE_FORMAT(birthday, '$year-%m-%d'), '%Y-%m-%d') BETWEEN '$start' AND '$end' ")
            ->get()
            ->map(function ($person) use ($endDate) {
                return new PersonBirthdayEvent($person, $endDate->year);
            });
    }
}
