<?php

namespace LaravelEnso\Calendar\Http\Controllers\Events;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\Http\Requests\ValidateEventIndex;
use LaravelEnso\Calendar\Http\Responses\Events;

class Index extends Controller
{
    public function __invoke(ValidateEventIndex $request)
    {
        return new Events();
    }
}
