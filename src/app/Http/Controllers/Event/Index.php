<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Http\Responses\Events;
use LaravelEnso\Calendar\app\Http\Resources\Event as Resource;

class Index extends Controller
{
    public function __invoke(Request $request)
    {
        return Resource::collection(
            (new Events($request))->get()
        );
    }
}
