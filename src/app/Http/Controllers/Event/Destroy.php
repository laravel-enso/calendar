<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Event;

class Destroy extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Event $event, $updateType)
    {
        $this->authorize('handle', $event);

        $event->deleteEvent($updateType);

        return ['message' => __('The event was successfully deleted')];
    }
}
