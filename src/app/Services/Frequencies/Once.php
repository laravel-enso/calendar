<?php

namespace LaravelEnso\Calendar\app\Services\Frequencies;

use Illuminate\Support\Collection;

class Once extends Frequency
{
    protected function dates(): Collection
    {
        return collect();
    }
}
