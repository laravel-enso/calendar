<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Calendar;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Facades\Calendars;
use LaravelEnso\Calendar\app\Http\Resources\Calendar;

class Index extends Controller
{
    public function __invoke()
    {
        return Calendar::collection(Calendars::all());
    }
}
