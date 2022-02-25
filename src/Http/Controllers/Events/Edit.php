<?php

namespace LaravelEnso\Calendar\Http\Controllers\Events;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\Forms\Builders\Event as Form;
use LaravelEnso\Calendar\Models\Event;

class Edit extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Event $event, Form $form)
    {
        $this->authorize('handle', $event);

        return ['form' => $form->edit($event)];
    }
}
