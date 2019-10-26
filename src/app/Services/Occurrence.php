<?php

namespace LaravelEnso\Calendar\app\Services;

use Carbon\Carbon;
use LaravelEnso\Calendar\app\DTOs\Route;
use LaravelEnso\Calendar\app\Contracts\Calendar;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;
use LaravelEnso\Calendar\app\Contracts\RoutableEvent;

class Occurrence implements ProvidesEvent
{
    protected $event;
    protected $date;

    public function __construct($event, Carbon $date)
    {
        $this->event = $event;
        $this->date = $date;
    }

    public function getKey()
    {
        return $this->event->getKey();
    }

    public function title(): string
    {
        return $this->event->title();
    }

    public function body(): ?string
    {
        return $this->event->body();
    }

    public function start(): Carbon
    {
        return $this->event->start()->addDays($this->days());
    }

    public function end(): Carbon
    {
        return $this->event->end()->addDays($this->days());
    }

    public function location(): ?string
    {
        return $this->event->location();
    }

    public function getCalendar(): Calendar
    {
        return $this->event->getCalendar();
    }

    public function frequence(): int
    {
        return $this->event->frequence();
    }

    public function recurrenceEnds(): ?Carbon
    {
        return $this->event->recurrenceEnds();
    }

    public function allDay(): bool
    {
        return $this->event->allDay();
    }

    public function readonly(): bool
    {
        return $this->event->readonly();
    }

    public function route(): ?Route
    {
        return $this->event instanceof RoutableEvent
            ? $this->event->route()
            : null;
    }

    private function days()
    {
        return $this->event->start()
            ->diffInDays($this->date);
    }
}
