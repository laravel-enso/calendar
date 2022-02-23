<?php

namespace LaravelEnso\Calendar\Http\Controllers\Calendar;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\Forms\Builders\Calendar;

class Create extends Controller
{
    public function __invoke(Calendar $form)
    {
        return ['form' => $form->create()];
    }
}
