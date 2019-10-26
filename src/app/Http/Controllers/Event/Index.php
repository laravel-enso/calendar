<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Http\Responses\Events;
use LaravelEnso\Calendar\app\Http\Requests\ValidateEventIndexRequest;

class Index extends Controller
{
    public function __invoke(ValidateEventIndexRequest $request)
    {
        return new Events();
    }
}
