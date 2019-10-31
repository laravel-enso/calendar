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
        return Colors::Green;
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
        return Person::query()
            ->whereRaw("DATE_FORMAT(birthday, '%m-%d') >= ? ", [$startDate->format('m-d')])
            ->whereRaw("DATE_FORMAT(birthday, '%m-%d') <= ? ", [$endDate->format('m-d')])
            ->get()
            ->map(function ($person) use ($startDate) {
                return new PersonBirthdayEvent($person, $startDate->year);
            });
    }
}
