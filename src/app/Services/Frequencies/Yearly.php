<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

class Yearly extends Frequency
{
    protected $frequency = Frequencies::Yearly;

    public function query(Builder $query)
    {
        $query->where('frequence', $this->frequency)
            ->where('recurrence_ends_at', '>=', $this->startDate)
            ->when($this->durationInMonths() === 0, function (Builder $query) {
                $query->whereDay('starts_at', '>=', $this->startDate->format('d'))
                    ->whereDay('starts_at', '<=', $this->endDate->format('d'))
                    ->whereMonth('starts_at', $this->startDate->format('m'));
            })->when($this->durationInMonths() === 1 && $this->durationInDays() < 30, function (Builder $query) {
                $query->whereDay('starts_at', '>=', $this->startDate->format('d'))
                    ->orWhereDay('starts_at', '<=', $this->endDate->format('d'));
            })->when($this->durationInMonths() < 12, function (Builder $query) {
                $query->when($this->startDate->month <= $this->endDate->month, function ($query) {
                    $query->whereMonth('starts_at', '>=', $this->startDate->format('m'))
                        ->whereMonth('starts_at', '<=', $this->endDate->format('m'));
                })->when($this->startDate->month > $this->endDate->month, function ($query) {
                    $query->whereMonth('starts_at', '>=', $this->startDate->format('m'))
                        ->orWhereMonth('starts_at', '<=', $this->endDate->format('m'));
                });
            });

    }

    protected function dates(ProvidesEvent $event): Collection
    {
        return $this->interval($event)
            ->filter(function (Carbon $date) use ($event) {
                return $date->month === $event->start()->month
                    && $date->day === $event->start()->day;
            });
    }
}
