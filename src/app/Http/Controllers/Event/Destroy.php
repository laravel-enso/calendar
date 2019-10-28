<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\UpdateType;
use LaravelEnso\Calendar\app\Services\Frequency;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LaravelEnso\Calendar\app\Http\Requests\ValidateEventUpdate;

class Destroy extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Event $event, $updateType)
    {
        $this->authorize('handle', $event);

        $event->delete();

        if ($updateType === UpdateType::All) {
            (new Frequency($event))->delete();
        }

        return ['message' => __('The event was successfully deleted')];
    }
}
