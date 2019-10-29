<?php

namespace LaravelEnso\Calendar\app\Services\Frequency;

use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Services\Frequency\Repeats\Once;
use LaravelEnso\Calendar\app\Services\Frequency\Repeats\Daily;
use LaravelEnso\Calendar\app\Services\Frequency\Repeats\Weekly;
use LaravelEnso\Calendar\app\Services\Frequency\Repeats\Yearly;
use LaravelEnso\Calendar\app\Services\Frequency\Repeats\Monthly;
use LaravelEnso\Calendar\app\Services\Frequency\Repeats\Weekday;

abstract class Frequency
{
    private static $frequencies = [
        Frequencies::Once => Once::class,
        Frequencies::Daily => Daily::class,
        Frequencies::Weekly => Weekly::class,
        Frequencies::Weekdays => Weekday::class,
        Frequencies::Monthly => Monthly::class,
        Frequencies::Yearly => Yearly::class,
    ];

    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    protected function dates()
    {
        $class = self::$frequencies[$this->event->frequence()];

        return (new $class($this->parent()))->dates();
    }

    protected function parent()
    {
        return $this->isParent()
            ? $this->event
            : $this->event->parent;
    }

    protected function isParent()
    {
        return $this->event->parent_id === null;
    }

    protected function replicate($date)
    {
        return $this->event->replicate(['id'])->fill([
            'parent_id' => $this->parent()->id,
            'starts_on' => $date,
            'ends_on' => $date,
        ]);
    }
}
