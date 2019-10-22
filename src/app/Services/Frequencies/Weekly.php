<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

class Weekly extends BaseFrequency
{
    protected $frequency = Frequencies::Weekly;

    public function query(Builder $query)
    {
        $query->where('frequence', $this->frequency)
            ->where('recurrence_ends_at', '>=', $this->startDate())
            ->where('starts_at', '<=', $this->endDate());
    }

    protected function frequency()
    {
        return Frequencies::Weekly;
    }

    protected function dates(ProvidesEvent $event)
    {
        return $this->period($event)
            ->filter(function (Carbon $date) use ($event) {
                return $event->start()->dayOfWeek === $date->dayOfWeek;
            });
    }
}
