<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Illuminate\Database\Eloquent\Builder;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

class Once extends BaseFrequency
{
    protected $frequency = Frequencies::Once;

    public function query(Builder $query)
    {
         $query->where('frequence', $this->frequency)
             ->where('starts_at', '<=', $this->endDate())
             ->where('ends_at', '>=', $this->startDate());
    }

    protected function dates(ProvidesEvent $event)
    {
        return collect([$event->start()]);
    }
}
