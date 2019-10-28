<?php

namespace LaravelEnso\Calendar\app\Services;

use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Services\Frequencies\Once;
use LaravelEnso\Calendar\app\Services\Frequencies\Daily;
use LaravelEnso\Calendar\app\Services\Frequencies\Weekly;
use LaravelEnso\Calendar\app\Services\Frequencies\Yearly;
use LaravelEnso\Calendar\app\Services\Frequencies\Monthly;
use LaravelEnso\Calendar\app\Services\Frequencies\Weekday;

class Frequency
{
    private static $frequencies = [
        Frequencies::Once => Once::class,
        Frequencies::Daily => Daily::class,
        Frequencies::Weekly => Weekly::class,
        Frequencies::Weekdays => Weekday::class,
        Frequencies::Monthly => Monthly::class,
        Frequencies::Yearly => Yearly::class,
    ];

    private $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function update()
    {
        $this->frequency()->update();
    }

    public function insert()
    {
        $this->frequency()->insert();
    }

    public function delete()
    {
        $this->frequency()->delete();
    }

    private function frequency()
    {
        $class = self::$frequencies[$this->event->frequence()];

        return new $class($this->event);
    }
}
