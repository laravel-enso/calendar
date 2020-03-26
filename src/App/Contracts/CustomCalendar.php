<?php

namespace LaravelEnso\Calendar\App\Contracts;

use Carbon\Carbon;
use Illuminate\Support\Collection;

interface CustomCalendar extends Calendar
{
    public function events(Carbon $startDate, Carbon $endDate): Collection;
}
