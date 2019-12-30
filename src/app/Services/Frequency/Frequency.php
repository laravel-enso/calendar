<?php

namespace LaravelEnso\Calendar\App\Services\Frequency;

use LaravelEnso\Calendar\App\Enums\Frequencies;
use LaravelEnso\Calendar\App\Models\Event;
use LaravelEnso\Calendar\App\Services\Frequency\Repeats\Daily;
use LaravelEnso\Calendar\App\Services\Frequency\Repeats\Monthly;
use LaravelEnso\Calendar\App\Services\Frequency\Repeats\Once;
use LaravelEnso\Calendar\App\Services\Frequency\Repeats\Weekday;
use LaravelEnso\Calendar\App\Services\Frequency\Repeats\Weekly;
use LaravelEnso\Calendar\App\Services\Frequency\Repeats\Yearly;

abstract class Frequency
{
    protected Event $event;

    private static $options = [
        Frequencies::Once => Once::class,
        Frequencies::Daily => Daily::class,
        Frequencies::Weekly => Weekly::class,
        Frequencies::Weekdays => Weekday::class,
        Frequencies::Monthly => Monthly::class,
        Frequencies::Yearly => Yearly::class,
    ];

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    protected function dates($frequency, $start, $end)
    {
        $class = self::$options[$frequency];

        return (new $class($start, $end))->dates();
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
        return $this->event->replicate()->fill([
            'parent_id' => $this->parent()->id,
            'start_date' => $date,
            'end_date' => $date,
        ]);
    }
}
