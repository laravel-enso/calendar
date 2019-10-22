<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Destroy extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Event $event)
    {
        $this->authorize('handle', $event);

        $event->delete();

        return ['message' => __('The event was successfully deleted')];
    }
}
