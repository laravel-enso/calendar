<?php

namespace LaravelEnso\Calendar\Http\Controllers\Calendar;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\Http\Requests\ValidateCalendar;
use LaravelEnso\Calendar\Http\Resources\Calendar as Resource;
use LaravelEnso\Calendar\Models\Calendar;

class Store extends Controller
{
    public function __invoke(ValidateCalendar $request, Calendar $calendar)
    {
        $calendar->fill($request->validated())->save();

        return [
            'message' => __('The calendar was created!'),
            'calendar' => new Resource($calendar),
        ];
    }
}
