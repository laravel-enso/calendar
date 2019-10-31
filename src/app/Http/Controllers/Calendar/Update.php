<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Calendar;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Calendar;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LaravelEnso\Calendar\app\Http\Resources\Calendar as Resource;
use LaravelEnso\Calendar\app\Http\Requests\ValidateCalendarRequest;

class Update extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateCalendarRequest $request, Calendar $calendar)
    {
        $this->authorize('handle', $calendar);

        $calendar->update($request->validated());

        return [
            'message' => __('The calendar was updated!'),
            'calendar' => new Resource($calendar),
        ];
    }
}
