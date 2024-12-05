<?php

namespace LaravelEnso\Calendar\Http\Controllers\Events;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\Http\Requests\ValidateEvent;
use LaravelEnso\Calendar\Http\Resources\Event as Resource;
use LaravelEnso\Calendar\Models\Event;

class Update extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateEvent $request, Event $event)
    {
        $this->authorize('handle', $event);

        $event->fill($request->validatedExcept('attendees', 'reminders', 'updateType'));

        if ($event->isDirty()) {
            $event->store($request->get('updateType'));
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
