<?php

namespace LaravelEnso\Calendar\app\Services\Frequency;

use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\UpdateType;

class Update extends Frequency
{
    private static $attributes = ['starts_time', 'ends_time', 'recurrence_ends_at'];

    public function handle($updateType)
    {
        if ($updateType === UpdateType::Single) {
            return;
        }

        $this->insert()->updateTimes($updateType)->deleteOutside();
    }

    protected function insert()
    {
        if (! $this->intervalChanged()) {
            return $this;
        }

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

    protected function updateTimes($updateType)
    {
        collect($this->event->getChanges())
            ->intersectByKeys(collect(static::$attributes)->flip())
            ->whenNotEmpty(function ($attributes) use ($updateType) {
                Event::sequence($this->parent()->id)
                    ->when($updateType === UpdateType::Futures, function ($query) {
                        $query->where('starts_date', '>', $this->event->starts_date);
                    })->update($attributes->toArray());
            });

        return $this;
    }

    protected function deleteOutside()
    {
        if ($this->intervalChanged()) {
            Event::whereParentId($this->parent()->id)->where(function ($query) {
                $query->where('starts_date', '<=', $this->parent()->starts_date)
                    ->orWhere('ends_date', '>', $this->event->recurrenceEnds());
            })->delete();
        }

        return $this;
    }

    protected function eventDates()
    {
        return collect([$this->parent()])
            ->concat($this->parent()->events)
            ->map(function (Event $event) {
                return $event->starts_date->toDateString();
            });
    }

    protected function intervalChanged(): bool
    {
        return $this->event->wasChanged('recurrence_ends_at')
            || ($this->isParent() && $this->event->wasChanged('starts_date'));
    }
}
