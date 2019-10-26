<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

class Weekly extends Frequency
{
    protected $frequency = Frequencies::Weekly;

    public function query(Builder $query)
    {
        $query->whereFrequence($this->frequency)
            ->where('recurrence_ends_at', '>=', $this->startDate)
            ->where('starts_at', '<=', $this->endDate);
    }

    protected function dates(ProvidesEvent $event): Collection
    {
        return $this->interval($event)
            ->filter(function (Carbon $date) use ($event) {
                return $event->start()->dayOfWeek === $date->dayOfWeek;
            });
    }
}
