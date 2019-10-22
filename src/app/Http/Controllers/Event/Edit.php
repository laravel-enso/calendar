<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Forms\Builders\EventForm;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Edit extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Event $event, EventForm $form)
    {
        $this->authorize('handle', $event);

        return ['form' => $form->edit($event)];
    }
}
