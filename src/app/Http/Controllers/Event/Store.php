<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Services\Frequency;
use LaravelEnso\Calendar\app\Http\Requests\ValidateEventStore;
use LaravelEnso\Calendar\app\Http\Resources\Event as Resource;

class Store extends Controller
{
    public function __invoke(ValidateEventStore $request, Event $event)
    {
        tap($event)->fill($request->validated())
            ->save();

        $event->reminders()->createMany($request->reminders());
        $event->attendees()->sync($request->get('attendees'));

        (new Frequency($event))->insert();

        return [
            'message' => __('The event was created!'),
            'event' => new Resource($event),
        ];
    }
}
