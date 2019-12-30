<?php

namespace LaravelEnso\Calendar\App\Services\Frequency\Repeats;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class Weekly extends Repeat
{
    public function dates(): Collection
    {
        return $this->interval()
            ->filter(fn (Carbon $date) => $this->start->dayOfWeek === $date->dayOfWeek);
    }
}
