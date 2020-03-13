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

class Create
{
    protected Event $event;
    protected array $stub;

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

    public function handle()
    {
        $this->stub = $this->event->replicate()
            ->fill(['parent_id' => $this->event->id])
            ->toArray();

        $this->interval()
            ->reject->equalTo($this->event->start_date)
            ->map(fn ($date) => $this->map($date))
            ->whenNotEmpty(fn ($events) => Event::insert($events->toArray()));

        return $this;
    }

    protected function interval()
    {
        $class = self::$options[$this->event->frequency()];

        return (new $class($this->event->start_date, $this->event->recurrence_ends_at))->dates();
    }

    protected function map($date)
    {
        $this->stub['start_date'] = $this->stub['end_date'] = $date->format('Y-m-d');

        return $this->stub;
    }
}
