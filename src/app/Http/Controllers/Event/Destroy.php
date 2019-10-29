<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Destroy extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Event $event, $updateType)
    {
        $this->authorize('handle', $event);

        tap($event)->deleteEvent($updateType);

        return ['message' => __('The event was successfully deleted')];
    }
}
