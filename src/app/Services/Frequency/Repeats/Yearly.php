<?php

namespace LaravelEnso\Calendar\app\Services\Frequency\Repeats;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class Yearly extends Repeat
{
    public function dates(): Collection
    {
        return $this->interval()
            ->filter(fn (Carbon $date) => $this->sameMonth($date));
    }

    private function sameMonth($date)
    {
        return $date->month === $this->start->month
            && $date->day === $this->start->day;
    }
}
