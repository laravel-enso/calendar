<?php

namespace LaravelEnso\Calendar\Services\Frequency\Types;

use Carbon\Carbon;
use Illuminate\Support\Collection;

abstract class Type
{
    public function __construct(
        protected Carbon $start,
        protected Carbon $end
    ) {
    }

    abstract public function dates(): Collection;

    protected function interval()
    {
        return new Collection(
            $this->start->daysUntil($this->end->endOfDay())->toArray()
        );
    }
}
