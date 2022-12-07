<?php

namespace LaravelEnso\Calendar\Http\Controllers\Events;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\Http\Requests\ValidateEventRequest;
use LaravelEnso\Calendar\Http\Resources\Event as Resource;
use LaravelEnso\Calendar\Models\Calendar;
use LaravelEnso\Calendar\Models\Event;

class Store extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateEventRequest $request, Event $event)
    {
        $this->authorize('handle', Calendar::cacheGet($request->get('calendar_id')));

        $event->fill($request->safe()->except('attendees', 'reminders'))->store();
        $event->reminders()->createMany($request->reminders());
        $event->attendees()->sync($request->get('attendees'));

        return [
            'message' => __('The event was created!'),
            'event' => new Resource($event),
        ];
    }
}
