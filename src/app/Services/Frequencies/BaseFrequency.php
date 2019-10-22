<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Services\Request;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;
use LaravelEnso\Calendar\app\Services\FrequentEvent;

abstract class BaseFrequency
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    abstract public function query(Builder $query);

    public function events(Collection $events) :Collection {
        return $this->filter($events)->reduce(function ($events, $event) {
            return $events->concat($this->frequentEvents($event));
        }, collect());
    }

    abstract protected function dates(ProvidesEvent $event);

    protected function startDate()
    {
        return $this->request->startDate();
    }

    protected function endDate()
    {
        return $this->request->endDate();
    }

    protected function filter($events)
    {
        return $events->filter(function (ProvidesEvent $event) {
            return $event->frequence() === $this->frequency;
        });
    }

    protected function period(ProvidesEvent $event)
    {
        $start = $this->startDate()->max($event->start());
        $end = $event->recurrenceEnds()->min($this->endDate());

        return collect($start->daysUntil($end)->toArray());
    }

    protected function frequentEvents(ProvidesEvent $event)
    {
        return $this->dates($event)
            ->reduce(function ($events, Carbon $date) use ($event) {
                return $events->push(new FrequentEvent($event, $date));
            }, collect());
    }
}
