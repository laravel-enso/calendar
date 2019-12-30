<?php

namespace LaravelEnso\Calendar\App\Services\Frequency;

use LaravelEnso\Calendar\App\Models\Event;

class Create extends Frequency
{
    public function handle()
    {
        $this->interval()
            ->reject->equalTo($this->event->start_date)
            ->map(fn ($date) => $this->replicate($date)->attributesToArray())
            ->whenNotEmpty(fn ($events) => Event::insert($events->toArray()));

        return $this;
    }

    protected function interval()
    {
        return $this->dates(
            $this->event->frequency(),
            $this->event->start_date,
            $this->event->recurrence_ends_at ?? $this->event->start_date
        );
    }
}
