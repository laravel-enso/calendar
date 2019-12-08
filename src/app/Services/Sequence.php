<?php

namespace LaravelEnso\Calendar\app\Services;

use Carbon\Carbon;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Models\Event;

class Sequence
{
    private $root;

    public function __construct(Event $event)
    {
        $this->root = $event->parent_id
            ? $event->parent
            : $event;
    }

    public function break(Event $event, $gap = 0)
    {
        $nextParent = $this->nextParent($event->start_date, $gap) ?? $event;

        if ($this->isNotParent($nextParent)) {
            $this->makeParent($nextParent);
            $this->removeRecurrenceBetween($event, $nextParent);
            $this->updateRecurrenceEndBefore($event);
        }
    }

    private function nextParent(Carbon $date, $next)
    {
        return $this->root->events
            ->push($this->root)
            ->sortBy('start_date')
            ->filter(function ($event) use ($date) {
                return $date->lte($event->start_date);
            })->values()->get($next);
    }

    private function isNotParent($event)
    {
        return $event->parent_id !== null;
    }

    private function makeParent($nextParent)
    {
        Event::where('start_date', '>', $nextParent->start_date)
            ->whereParentId($this->root->id)->update([
                'parent_id' => $nextParent->id,
            ]);

        $nextParent->update(['parent_id' => null]);
    }

    private function removeRecurrenceBetween($event, $nextParent)
    {
        Event::between(
            $event->start_date, $nextParent->start_date->clone()->subDay()
        )->whereParentId($this->root->id)->update([
            'parent_id' => null,
            'frequency' => Frequencies::Once,
            'recurrence_ends_at' => null,
        ]);
    }

    private function updateRecurrenceEndBefore($event)
    {
        Event::sequence($this->root->id)->update([
            'recurrence_ends_at' => $event->start_date->clone()->subDay(),
        ]);
    }
}
