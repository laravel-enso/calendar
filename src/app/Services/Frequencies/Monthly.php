<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

class Monthly extends Frequency
{
    protected function dates(): Collection
    {
        return $this->interval()
            ->filter(function (Carbon $date) {
                return $date->day === $this->event->start()->day;
            });
    }
}
