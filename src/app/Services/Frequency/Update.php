<?php

namespace LaravelEnso\Calendar\app\Services\Frequency;

use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\UpdateType;

class Update extends Frequency
{
    private static $attributes = ['starts_time', 'ends_time', 'recurrence_ends_at'];

    public function handle($updateType)
    {
        if ($updateType === UpdateType::All) {
            $this->insert()->updateTimes()->deleteOutside();
        }
    }

    protected function insert()
    {
        $eventDates = $this->eventDates();

        $this->dates()
            ->reject(function ($date) use ($eventDates) {
                return $eventDates->contains($date->toDateString());
            })->map(function ($date) {
                return $this->replicate($date)->attributesToArray();
            })->whenNotEmpty(function ($events) {
                Event::insert($events->toArray());
            });

        return $this;
    }

    protected function updateTimes()
    {
        collect($this->event->getChanges())
            ->intersectByKeys(collect(static::$attributes)->flip())
            ->whenNotEmpty(function ($attributes) {
                Event::whereId($this->parent()->id)
                    ->orWhere('parent_id', $this->parent()->id)
                    ->update($attributes->toArray());
            });

        return $this;
    }

    protected function deleteOutside()
    {
        if ($this->event->wasChanged(['starts_on', 'recurrence_ends_at'])) {
            Event::whereParentId($this->parent()->id)->where(function ($query) {
                $query->where('starts_on', '<=', $this->parent()->starts_on)
                    ->orWhere('ends_on', '>', $this->event->recurrenceEnds());
            })->delete();
        }

        return $this;
    }

    protected function eventDates()
    {
        return collect([$this->parent()])
            ->concat($this->parent()->events)
            ->map(function (Event $event) {
                return $event->starts_on->toDateString();
            });
    }
}
