<?php

use Carbon\Carbon;
use Illuminate\Support\Collection;
use LaravelEnso\Calendar\Contracts\Calendar as CalendarContract;
use LaravelEnso\Calendar\Contracts\CustomCalendar;
use LaravelEnso\Calendar\Contracts\ProvidesEvent;
use LaravelEnso\Calendar\Enums\Colors;
use LaravelEnso\Calendar\Enums\Frequencies;

class CalendarTestCustomCalendar implements CustomCalendar
{
    public function getKey()
    {
        return 'calendar-test-custom';
    }

    public function name(): string
    {
        return 'Custom Calendar';
    }

    public function color(): string
    {
        return Colors::Blue;
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
        return Collection::make([
            new CalendarTestProvidedEvent($this, $startDate->copy()->addHour(), 'custom-event'),
        ]);
    }
}

class CalendarTestPrivateCustomCalendar extends CalendarTestCustomCalendar
{
    public function getKey()
    {
        return 'calendar-test-private-custom';
    }

    public function private(): bool
    {
        return true;
    }
}

class CalendarTestProvidedEvent implements ProvidesEvent
{
    public function __construct(
        private CalendarContract $calendar,
        private Carbon $start,
        private string $title,
    ) {
    }

    public function getKey()
    {
        return $this->title;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function body(): ?string
    {
        return 'custom-body';
    }

    public function start(): Carbon
    {
        return $this->start->copy();
    }

    public function end(): Carbon
    {
        return $this->start->copy()->addHour();
    }

    public function location(): ?string
    {
        return 'remote';
    }

    public function getCalendar(): CalendarContract
    {
        return $this->calendar;
    }

    public function frequency(): int
    {
        return Frequencies::Once;
    }

    public function recurrenceEnds(): ?Carbon
    {
        return null;
    }

    public function allDay(): bool
    {
        return false;
    }

    public function readonly(): bool
    {
        return true;
    }
}
