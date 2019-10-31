<?php

namespace LaravelEnso\Calendar\app\Services\Frequency\Repeats;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class Monthly extends Repeat
{
    public function dates(): Collection
    {
        return $this->interval()
            ->filter(function (Carbon $date) {
                return $date->day === $this->event->starts_date->day;
            });
    }
}
