<?php

namespace LaravelEnso\Calendar\app\Services\Frequency\Repeats;

use Illuminate\Support\Collection;
use LaravelEnso\Calendar\app\Models\Event;

abstract class Repeat
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    abstract public function dates(): Collection;

    protected function interval()
    {
        $start = $this->event->starts_date;
        $end = $this->event->recurrenceEnds();

        return collect($start->daysUntil($end)->toArray());
    }
}
