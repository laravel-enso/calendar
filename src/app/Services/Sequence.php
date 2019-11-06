<?php

namespace LaravelEnso\Calendar\app\Services;

use Carbon\Carbon;
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


    public function break(Event $event)
    {
        if ($this->isParent($event)) {
            return;
        }

        Event::where('start_date', '>', $event->start_date)
            ->whereParentId($this->root->id)->update([
                'parent_id' => $event->id,
            ]);

        $event->update([
            'parent_id' => null
        ]);

        Event::sequence($this->root->id)->update([
            'recurrence_ends_at' => $event->start_date->clone()
                ->subDays()
        ]);
    }

    public function extract(Event $event)
    {
        if ($nextEvent = $this->nextEvent($event->start_date)){
            $this->break($nextEvent);
        }

        $event->update([
            'parent_id'=> null,
        ]);
    }

    protected function nextEvent(Carbon $date)
    {
        return $this->root->events
            ->sortBy('start_date')
            ->first(function ($event) use ($date) {
                return $date->lt($event->start_date);
            });
    }

    protected function isParent($event = null)
    {
        return $event->parent_id === null;
    }

    public function root()
    {
        return $this->root;
    }

    protected function isLast($event = null)
    {
        return $this->root->events->isEmpty()
            || $this->root->events->last()->is($event);
    }
}

