<?php

namespace LaravelEnso\Calendar\app\Services;

use Carbon\Carbon;
use LaravelEnso\Calendar\app\DTOs\Route;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

class FrequentEvent implements ProvidesEvent
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

    public function title()
    {
        return $this->event->title();
    }

    public function body()
    {
        return $this->event->body();
    }

    public function start(): Carbon
    {
        return $this->event->start()->setDateFrom($this->date);
    }

    public function end(): Carbon
    {
        return $this->event->end()->setDateFrom($this->date);
    }

    public function location()
    {
        return $this->event->location();
    }

    public function getCalendar()
    {
        return $this->event->getCalendar();
    }

    public function frequence()
    {
        return $this->event->frequence();
    }

    public function recurrenceEnds(): ?Carbon
    {
        return $this->event->recurrenceEnds();
    }

    public function allDay()
    {
        return $this->event->allDay();
    }

    public function readonly()
    {
        return $this->event->readonly();
    }

    public function route(): ?Route
    {
        return $this->event->route();
    }
}
