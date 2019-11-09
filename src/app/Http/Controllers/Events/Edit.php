<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Events;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Forms\Builders\EventForm;
use LaravelEnso\Calendar\app\Models\Event;

class Edit extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Event $event, EventForm $form)
    {
        $this->authorize('handle', $event);

        return ['form' => $form->edit($event)];
    }
}
