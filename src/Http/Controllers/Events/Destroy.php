<?php

namespace LaravelEnso\Calendar\Http\Controllers\Events;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
<<<<<<< Updated upstream
use LaravelEnso\Calendar\Http\Requests\ValidateEventDestroyRequest;
=======
use LaravelEnso\Calendar\Enums\UpdateType;
use LaravelEnso\Calendar\Http\Requests\ValidateEventDestroy;
>>>>>>> Stashed changes
use LaravelEnso\Calendar\Models\Event;

class Destroy extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateEventDestroyRequest $request, Event $event)
    {
        $this->authorize('handle', $event);

        $event->remove(UpdateType::tryFrom($request->get('updateType')));

        return ['message' => __('The event was successfully deleted')];
    }
}
