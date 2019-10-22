<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

class Yearly extends BaseFrequency
{
    protected $frequency = Frequencies::Yearly;

    public function query(Builder $query)
    {
        $query->where('frequence', $this->frequency)
            ->where('recurrence_ends_at', '>=', $this->startDate())
            ->whereDay('starts_at', '>=', $this->startDate()->format('d'))
            ->whereDay('starts_at', '<=', $this->endDate()->format('d'))
            ->whereMonth('starts_at', $this->startDate()->format('m'));
    }

    protected function dates(ProvidesEvent $event) :Collection
    {
        return $this->period($event)
            ->filter(function (Carbon $date) use ($event) {
                return $date->month === $event->start()->month
                    && $date->day === $event->start()->day;
            });
    }
}
