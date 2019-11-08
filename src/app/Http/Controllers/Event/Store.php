<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Http\Requests\ValidateEventRequest;
use LaravelEnso\Calendar\app\Http\Resources\Event as Resource;
use LaravelEnso\Calendar\app\Models\Calendar;
use LaravelEnso\Calendar\app\Models\Event;

class Store extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateEventRequest $request, Event $event)
    {
        $this->authorize(
            'handle', Calendar::cacheGet($request->get('calendar_id'))
        );

        $event = $event->createEvent($request->validated())
            ->createReminders($request->reminders())
            ->syncAttendees($request->get('attendees'));

        return [
            'message' => __('The event was created!'),
            'event' => new Resource($event),
        ];
    }
}
