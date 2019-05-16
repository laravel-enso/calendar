<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Forms\Builders\EventForm;

class Edit extends Controller
{
    public function __invoke(Event $event, EventForm $form)
    {
        $this->authorize('handle', $event);

        return ['form' => $form->edit($event)];
    }
}
