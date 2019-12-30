<?php

namespace LaravelEnso\Calendar\App\Http\Controllers\Calendar;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\App\Http\Requests\ValidateCalendarRequest;
use LaravelEnso\Calendar\App\Http\Resources\Calendar as Resource;
use LaravelEnso\Calendar\App\Models\Calendar;

class Store extends Controller
{
    public function __invoke(ValidateCalendarRequest $request, Calendar $calendar)
    {
        $calendar->fill($request->validated())->save();

        return [
            'message' => __('The calendar was created!'),
            'calendar' => new Resource($calendar),
        ];
    }
}
