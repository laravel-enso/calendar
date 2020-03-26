<?php

namespace LaravelEnso\Calendar\App\Http\Controllers\Events;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\App\Http\Requests\ValidateEventIndexRequest;
use LaravelEnso\Calendar\App\Http\Responses\Events;

class Index extends Controller
{
    public function __invoke(ValidateEventIndexRequest $request)
    {
        return new Events();
    }
}
