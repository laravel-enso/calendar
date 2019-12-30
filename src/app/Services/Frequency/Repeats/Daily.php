<?php

namespace LaravelEnso\Calendar\App\Services\Frequency\Repeats;

use Illuminate\Support\Collection;

class Daily extends Repeat
{
    public function dates(): Collection
    {
        return $this->interval();
    }
}
