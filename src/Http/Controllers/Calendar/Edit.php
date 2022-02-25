<?php

namespace LaravelEnso\Calendar\Http\Controllers\Calendar;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\Forms\Builders\Calendar as Form;
use LaravelEnso\Calendar\Models\Calendar;

class Edit extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Calendar $calendar, Form $form)
    {
        $this->authorize('handle', $calendar);

        return ['form' => $form->edit($calendar)];
    }
}
