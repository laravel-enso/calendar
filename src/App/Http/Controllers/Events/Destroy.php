<?php

namespace LaravelEnso\Calendar\App\Http\Controllers\Events;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\App\Http\Requests\ValidateEventDestroyRequest;
use LaravelEnso\Calendar\App\Models\Event;

class Destroy extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateEventDestroyRequest $request, Event $event)
    {
        $this->authorize('handle', $event);

        $event->remove($request->get('updateType'));

        return ['message' => __('The event was successfully deleted')];
    }
}
