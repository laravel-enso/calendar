<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Services\Request;
use LaravelEnso\Calendar\app\Services\Occurrence;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

abstract class Frequency
{
    protected $startDate;
    protected $endDate;
    protected $frequency;

    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    abstract public function query(Builder $query);

    abstract protected function dates(ProvidesEvent $event): Collection;

    public function events(Collection $events): Collection
    {
        return $this->filter($events)->reduce(function ($events, $event) {
            return $events->concat($this->matchingEvents($event));
        }, collect());
    }

    protected function durationInMonths(): int
    {
        return $this->startDate->diffInMonths($this->endDate);
    }

    protected function durationInDays(): int
    {
        return $this->startDate->diffInDays($this->endDate);
    }

    protected function filter($events)
    {
        return $events->filter(function (ProvidesEvent $event) {
            return $event->frequence() === $this->frequency;
        });
    }

    protected function interval(ProvidesEvent $event)
    {
        $start = $this->startDate->max($event->start());
        $end = $event->recurrenceEnds()->min($this->endDate);

        return collect($start->daysUntil($end)->toArray());
    }

    protected function matchingEvents(ProvidesEvent $event)
    {
        return $this->dates($event)
            ->reduce(function ($events, Carbon $date) use ($event) {
                return $events->push(new Occurrence($event, $date));
            }, collect());
    }
}
