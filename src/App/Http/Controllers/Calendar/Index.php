<?php

namespace LaravelEnso\Calendar\App\Http\Controllers\Calendar;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\App\Facades\Calendars;
use LaravelEnso\Calendar\App\Http\Resources\Calendar;

class Index extends Controller
{
    public function __invoke()
    {
        return Calendar::collection(Calendars::all());
    }
}
