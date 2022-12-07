<?php

namespace LaravelEnso\Calendar\Services;

use LaravelEnso\Calendar\Enums\Frequency;
use LaravelEnso\Calendar\Models\Event;

class Sequence
{
    private Event $currentParent;
    private bool $singular;

    public function __construct(private Event $event)
    {
    }

    public function extract()
    {
        $this->singular = true;
        $this->handle();
    }

    public function break()
    {
        $this->singular = false;
        $this->handle();
    }

    private function handle()
    {
        $this->currentParent = $this->currentParent();

        $this->updateParent()
            ->updateRecurrenceEnding()
            ->updateFrequency();
    }

    private function currentParent(): Event
    {
        return $this->event->parent_id ? $this->event->parent : $this->event;
    }

    private function updateParent()
    {
        $newParent = $this->newParent();

        if ($newParent) {
            $this->currentParent->events()
                ->where('start_date', '>', $newParent->start_date)
                ->update(['parent_id' => $newParent->id]);

            $newParent->update(['parent_id' => null]);
        }

        return $this;
    }

    private function newParent(): ?Event
    {
        return $this->singular
            ? $this->currentParent->events()
            ->where('start_date', '>', $this->event->getOriginal('start_date'))
            ->orderBy('start_date')
            ->first()
            : $this->event;
    }

    private function updateRecurrenceEnding()
    {
        $lastEvent = Event::sequence($this->currentParent->id)
            ->where('id', '<>', $this->event->id)
            ->orderByDesc('start_date')
            ->first();

        if ($lastEvent) {
            Event::sequence($this->currentParent->id)->update([
                'recurrence_ends_at' => $lastEvent->start_date,
            ]);
        }

        return $this;
    }

    private function updateFrequency()
    {
        if ($this->singular) {
            $this->event->update([
                'parent_id' => null,
                'frequency' => Frequency::Once,
                'recurrence_ends_at' => null,
            ]);
        }
    }
}
