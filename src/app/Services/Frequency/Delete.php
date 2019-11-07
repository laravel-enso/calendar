<?php

namespace LaravelEnso\Calendar\app\Services\Frequency;

use LaravelEnso\Calendar\app\Enums\UpdateType;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Services\Sequence;

class Delete extends Frequency
{
    public function handle($updateType)
    {
        if ($updateType === UpdateType::OnlyThisEvent) {
            return (new Sequence($this->event))->extract($this->event);
        }

        Event::sequence($this->parent()->id)
            ->when($updateType === UpdateType::ThisAndFutureEvents, function ($query) {
                $query->where('start_date', '>', $this->event->start_date);
            })->delete();
    }
}
