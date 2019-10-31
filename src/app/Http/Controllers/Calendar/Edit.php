<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Calendar;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Calendar;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LaravelEnso\Calendar\app\Forms\Builders\CalendarForm;

class Edit extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Calendar $calendar, CalendarForm $form)
    {
        $this->authorize('handle', $calendar);

        return ['form' => $form->edit($calendar)];
    }
}
