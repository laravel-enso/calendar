<?php

namespace LaravelEnso\Calendar\Services\Frequency\Types;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class Weekly extends Type
{
    public function dates(): Collection
    {
        return $this->interval()
            ->filter(fn (Carbon $date) => $this->start->dayOfWeek === $date->dayOfWeek);
    }
}
