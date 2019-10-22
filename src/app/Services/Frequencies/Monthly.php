<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

class Monthly extends BaseFrequency
{
    protected $frequency = Frequencies::Monthly;

    public function query(Builder $query)
    {
        $query->where('frequence', $this->frequency)
            ->where('recurrence_ends_at', '>=', $this->startDate())
            ->when($this->diffInMonths() === 0, function (Builder $query) {
                $query->whereDay('starts_at', '>=', $this->startDate()->format('d'))
                    ->whereDay('starts_at', '<=', $this->endDate()->format('d'));
            })->when($this->diffInMonths() === 1 && $this->diffInDays() < 30, function (Builder $query) {
                $query->whereDay('starts_at', '>=', $this->startDate()->format('d'))
                    ->orWhereDay('starts_at', '<=', $this->endDate()->format('d'));
            });
    }

    protected function dates(ProvidesEvent $event)
    {
        return $this->period($event)
            ->filter(function (Carbon $date) use ($event) {
                return $date->day === $event->start()->day;
            });
    }
}
