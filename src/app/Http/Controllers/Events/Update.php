<?php

namespace LaravelEnso\Calendar\App\Http\Controllers\Events;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\App\Http\Requests\ValidateEventRequest;
use LaravelEnso\Calendar\App\Http\Resources\Event as Resource;
use LaravelEnso\Calendar\App\Models\Event;

class Update extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateEventRequest $request, Event $event)
    {
        $this->authorize('handle', $event);

        $event->fill($request->validated());

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
