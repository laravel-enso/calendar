<?php

namespace LaravelEnso\Calendar\Services\Frequency\Types;

use Illuminate\Support\Collection;

class Weekday extends Type
{
    public function dates(): Collection
    {
        return $this->interval()->filter->isWeekDay();
    }
}
