<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\UpdateType;

abstract class FrequencyFollowing
{
    protected $event;

    private $updateType;

    public function __construct(Event $event, $updateType)
    {
        $this->event = $event;
        $this->updateType = $updateType;
    }

    abstract protected function dates(): Collection;

    public function generate()
    {
        $this->insert()->update()->delete();
    }

    protected function interval()
    {
        $start = $this->start();
        $end = $this->event->recurrenceEnds();

        return collect($start->daysUntil($end)->toArray());
    }

    public function insert()
    {
        $events = $this->dates()->filter(function ($date) {
                return $this->events()->first(function (Event $event) use ($date) {
                    //echo $date->toDateString().' === '. $event->start()->toDateString().PHP_EOL;
                    return $date->toDateString() === $event->start()->toDateString();
                }) === null;
            })->map(function ($date) {
                return $this->event->replicate(['id'])->fill([
                    'parent_id' => $this->parent()->id,
                    'starts_at' => $this->start()->setDateFrom($date),
                    'ends_at' => $this->event->end()->setDateFrom($date),
                ]);
            });

        Event::insert($events->map->attributesToArray()->toArray());

        return $this;
    }

    protected function update()
    {
        $startsAt = DB::raw("CONCAT(DATE(starts_at), ' {$this->start()->toTimeString()}')");
        $endsAt = DB::raw("CONCAT(DATE(ends_at), ' {$this->event->end()->toTimeString()}')");

        $updatable = collect();

        if ($this->event->isDirty('starts_at')) {
            $updatable->put('starts_at',
                DB::raw("CONCAT(DATE(starts_at), ' {$this->start()->toTimeString()}')"));
        }

        if ($this->event->isDirty('ends_at')) {
            $updatable->put('ends_at',
                DB::raw("CONCAT(DATE(ends_at), ' {$this->event->end()->toTimeString()}')"));
        }

        if ($this->event->isDirty('recurrence_ends_at')) {
            $updatable->put('ends_at', $this->event->recurrenceEnds());
        }


        if ($updatable->isNotEmpty()) {
            $this->query()->update($updatable->toArray());
        }

        return $this;
    }

    protected function delete()
    {
        $this->query()->where(function ($query) {
                $query->where('starts_at', '<', $this->start())
                    ->orWhere('ends_at', '>', $this->event->recurrenceEnds());
            })->delete();

        return $this;
    }

    protected function query()
    {
        switch ($this->updateType) {
            case UpdateType::Single:
                return Event::whereId($this->event->id);
            case UpdateType::All:
                return Event::whereParentId($this->parent()->id);
            default:
                return Event::whereParentId($this->parent()->id)
                    ->where('starts_at', '>', $this->start());
        }
    }

    protected function start()
    {
        return $this->updateType === UpdateType::All
            ? $this->parent()->start()
            : $this->event->start();
    }

    protected function parent()
    {
        return $this->isParent()
            ? $this->event->parent
            : $this->event;
    }

    protected function isParent()
    {
        return $this->event->parent_id;
    }

    private function events()
    {
        return collect([$this->parent()])
            ->concat($this->parent()->events);
    }
}
