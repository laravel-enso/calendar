<?php

namespace LaravelEnso\Calendar\App\Http\Controllers\Calendar;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\App\Http\Requests\ValidateCalendarRequest;
use LaravelEnso\Calendar\App\Http\Resources\Calendar as Resource;
use LaravelEnso\Calendar\App\Models\Calendar;

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
