<?php

namespace LaravelEnso\Calendar\app\Services;

use Carbon\Carbon;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\Frequencies;

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

        if ($this->isParent($nextParent)) {
            return;
        }

        Event::where('start_date', '>', $nextParent->start_date)
            ->whereParentId($this->root->id)->update([
                'parent_id' => $nextParent->id,
            ]);

        $nextParent->update([
            'parent_id' => null,
        ]);

        Event::between($event->start_date, $nextParent->start_date->clone()->subDay())
            ->whereParentId($this->root->id)->update([
                'parent_id' => null,
                'frequency' => Frequencies::Once,
                'recurrence_ends_at' => null,
            ]);

        Event::sequence($this->root->id)->update([
            'recurrence_ends_at' => $event->start_date->clone()
                ->subDay()->format('Y-m-d'),
        ]);
    }

    private function nextParent(Carbon $date, $next)
    {
        return $this->root->events
            ->push($this->root)
            ->sortBy('start_date')
            ->filter(function ($event) use ($date) {
                return $date->lte($event->start_date);
            })
            ->values()->get($next);
    }

    private function isParent($event = null)
    {
        return $event->parent_id === null;
    }
}
