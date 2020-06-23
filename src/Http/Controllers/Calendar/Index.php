<?php

namespace LaravelEnso\Calendar\Http\Controllers\Calendar;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\Facades\Calendars;
use LaravelEnso\Calendar\Http\Resources\Calendar;

class Index extends Controller
{
    public function __invoke()
    {
        return Calendar::collection(Calendars::all());
    }
}
