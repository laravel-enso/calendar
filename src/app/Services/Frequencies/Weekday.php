<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

class Weekday extends Frequency
{
    protected $frequency = Frequencies::Weekdays;

    public function query(Builder $query)
    {
        $query->whereFrequence($this->frequency)
            ->where('recurrence_ends_at', '>=', $this->startDate)
            ->where('starts_at', '<=', $this->endDate);
    }

    protected function dates(ProvidesEvent $event): Collection
    {
        return $this->interval($event)->filter->isWeekDay();
    }
}
