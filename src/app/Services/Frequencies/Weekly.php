<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

class Weekly extends Frequency
{
    protected function dates(): Collection
    {
        return $this->interval()
            ->filter(function (Carbon $date) {
                return $this->event->start()->dayOfWeek === $date->dayOfWeek;
            });
    }
}
