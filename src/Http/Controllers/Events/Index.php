<?php

namespace LaravelEnso\Calendar\Http\Controllers\Events;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\Http\Requests\ValidateEventIndexRequest;
use LaravelEnso\Calendar\Http\Responses\Events;

class Index extends Controller
{
    public function __invoke(ValidateEventIndexRequest $request)
    {
        return new Events();
    }
}
