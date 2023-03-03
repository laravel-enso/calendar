<?php

namespace LaravelEnso\Calendar\Services\Frequency\Types;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class Monthly extends Type
{
    public function dates(): Collection
    {
        return $this->interval()
            ->filter(fn (Carbon $date) => $date->day === $this->start->day);
    }
}