<?php

namespace LaravelEnso\Calendar\app\Services\Frequency\Repeats;

use Carbon\Carbon;
use Illuminate\Support\Collection;

abstract class Repeat
{
    protected Carbon $start;
    protected Carbon $end;

    public function __construct(Carbon $start, Carbon $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    abstract public function dates(): Collection;

    protected function interval()
    {
        return new Collection(
            $this->start->daysUntil($this->end->endOfDay())->toArray()
        );
    }
}
