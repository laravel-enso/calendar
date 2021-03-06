<?php

namespace LaravelEnso\Calendar\Http\Controllers\Events;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\Forms\Builders\EventForm;

class Create extends Controller
{
    public function __invoke(EventForm $form)
    {
        return ['form' => $form->create()];
    }
}
