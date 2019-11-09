<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Events;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Http\Requests\ValidateEventIndexRequest;
use LaravelEnso\Calendar\app\Http\Responses\Events;

class Index extends Controller
{
    public function __invoke(ValidateEventIndexRequest $request)
    {
        return new Events();
    }
}
