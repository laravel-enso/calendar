<?php

namespace LaravelEnso\Calendar\Http\Controllers\Events;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
<<<<<<< Updated upstream
use LaravelEnso\Calendar\Http\Requests\ValidateEventRequest;
=======
use LaravelEnso\Calendar\Enums\UpdateType;
use LaravelEnso\Calendar\Http\Requests\ValidateEvent;
>>>>>>> Stashed changes
use LaravelEnso\Calendar\Http\Resources\Event as Resource;
use LaravelEnso\Calendar\Models\Event;

class Update extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateEventRequest $request, Event $event)
    {
        $this->authorize('handle', $event);

        $event->fill($request->safe()->except('attendees', 'reminders', 'updateType'));

        if ($event->isDirty()) {
            $event->store(UpdateType::tryFrom($request->get('updateType')));
        }

        $event->reminders()->delete();
        $event->reminders()->createMany($request->reminders());
        $event->attendees()->sync($request->get('attendees'));

        return [
            'message' => __('The event was updated!'),
            'event' => new Resource($event),
        ];
    }
}
