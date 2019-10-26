<?php

namespace LaravelEnso\Calendar\app\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Services\Frequencies\Once;
use LaravelEnso\Calendar\app\Services\Frequencies\Daily;
use LaravelEnso\Calendar\app\Services\Frequencies\Weekly;
use LaravelEnso\Calendar\app\Services\Frequencies\Yearly;
use LaravelEnso\Calendar\app\Services\Frequencies\Monthly;
use LaravelEnso\Calendar\app\Services\Frequencies\Weekday;

class Frequency
{
    private static $frequencies = [
        Once::class,
        Daily::class,
        Weekly::class,
        Weekday::class,
        Monthly::class,
        Yearly::class,
    ];

    protected $startDate;
    protected $endDate;

    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function events(Builder $query): Collection
    {
        $events = $this->query($query)->get();

        return collect(static::$frequencies)
            ->reduce(function ($occurencies, $frequency) use ($events) {
                return $occurencies->concat(
                    $this->frequency($frequency)->events($events)
                );
            }, collect());
    }

    private function query(Builder $query): Builder
    {
        return $query->where(function ($query) {
            collect(static::$frequencies)->each(function ($frequency) use ($query) {
                $query->orWhere(function ($query) use ($frequency) {
                    $this->frequency($frequency)->query($query);
                });
            });
        });
    }

    private function frequency($frequency)
    {
        return new $frequency($this->startDate, $this->endDate);
    }
}
