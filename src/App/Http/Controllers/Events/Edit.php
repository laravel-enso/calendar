<?php

namespace LaravelEnso\Calendar\App\Http\Controllers\Events;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\App\Forms\Builders\EventForm;
use LaravelEnso\Calendar\App\Models\Event;

class Edit extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Event $event, EventForm $form)
    {
        $this->authorize('handle', $event);

        return ['form' => $form->edit($event)];
    }
}
