<?php

namespace LaravelEnso\Calendar\app\Services;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;
use LaravelEnso\Calendar\app\Services\Frequencies\Once;
use LaravelEnso\Calendar\app\Services\Frequencies\Daily;
use LaravelEnso\Calendar\app\Services\Frequencies\Weekly;
use LaravelEnso\Calendar\app\Services\Frequencies\Yearly;
use LaravelEnso\Calendar\app\Services\Frequencies\Weekday;
use LaravelEnso\Calendar\app\Services\Frequencies\Monthly;

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

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function events(Collection $events) :Collection
    {
        return collect(static::$frequencies)
            ->reduce(function ($result, $frequency) use ($events) {
                return $result->concat(
                    $this->frequency($frequency)->events($events)
                );
            }, collect());
    }

    public function query(Builder $query)
    {
        $query->where(function ($query) {
            collect(static::$frequencies)->each(function ($frequency) use ($query) {
                $query->orWhere(function ($query) use ($frequency) {
                    $this->frequency($frequency)->query($query);
                });
            });
        });
    }

    private function frequency($frequency)
    {
        return (new $frequency($this->request));
    }
}
