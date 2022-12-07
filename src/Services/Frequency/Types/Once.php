<?php

namespace LaravelEnso\Calendar\Services\Frequency\Types;

use Illuminate\Support\Collection;

class Once extends Type
{
    public function dates(): Collection
    {
        return new Collection();
    }
}
