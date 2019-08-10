<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Calendar;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Enums\Calendars;

class Index extends Controller
{
    public function __invoke()
    {
        return ['calendars' => Calendars::select()];
    }
}
