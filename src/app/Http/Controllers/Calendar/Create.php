<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Calendar;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Forms\Builders\CalendarForm;

class Create extends Controller
{
    public function __invoke(CalendarForm $form)
    {
        return ['form' => $form->create()];
    }
}
