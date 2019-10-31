<?php

namespace LaravelEnso\Calendar\app\Services\Frequency\Repeats;

use Illuminate\Support\Collection;

class Once extends Repeat
{
    public function dates(): Collection
    {
        return collect();
    }
}
