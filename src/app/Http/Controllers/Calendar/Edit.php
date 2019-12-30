<?php

namespace LaravelEnso\Calendar\App\Http\Controllers\Calendar;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\App\Forms\Builders\CalendarForm;
use LaravelEnso\Calendar\App\Models\Calendar;

class Edit extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Calendar $calendar, CalendarForm $form)
    {
        $this->authorize('handle', $calendar);

        return ['form' => $form->edit($calendar)];
    }
}
