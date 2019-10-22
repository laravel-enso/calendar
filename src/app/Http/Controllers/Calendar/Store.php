<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Calendar;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Calendar;
use LaravelEnso\Calendar\app\Http\Resources\Calendar as Resource;
use LaravelEnso\Calendar\app\Http\Requests\ValidateCalendarRequest;

class Store extends Controller
{
    public function __invoke(ValidateCalendarRequest $request, Calendar $calendar)
    {
        tap($calendar)->fill($request->validated())
            ->save();

        return [
            'message' => __('The calendar was created!'),
            'calendar' => new Resource($calendar),
        ];
    }
}
