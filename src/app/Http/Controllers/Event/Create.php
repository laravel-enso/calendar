<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use LaravelEnso\Calendar\app\Forms\Builders\EventForm;

class Create extends Controller
{
    public function __invoke(EventForm $form)
    {
        return ['form' => $form->create()];
    }
}
