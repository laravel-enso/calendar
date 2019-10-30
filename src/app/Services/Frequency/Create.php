<?php

namespace LaravelEnso\Calendar\app\Services\Frequency;

use LaravelEnso\Calendar\app\Models\Event;

class Create extends Frequency
{
    public function handle()
    {
        $this->dates()
            ->reject->equalTo($this->event->starts_date)
            ->map(function ($date) {
                return $this->replicate($date)->attributesToArray();
            })->whenNotEmpty(function ($events) {
                Event::insert($events->toArray());
            });

        return $this;
    }
}
