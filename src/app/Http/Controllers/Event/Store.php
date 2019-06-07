<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Http\Requests\ValidateEventStore;
use LaravelEnso\Calendar\app\Http\Resources\Event as Resource;

class Store extends Controller
{
    public function __invoke(ValidateEventStore $request, Event $event)
    {
        tap($event)->fill($request->validated())
            ->save();

        if (! empty($request->get('reminders'))) {
            $event->reminders()->createMany(
                collect($request->get('reminders'))
                    ->filter(function ($reminder) {
                        return ! empty($reminder['remind_at']);
                    })->toArray()
            );
        }

        return [
            'message' => __('The event was created!'),
            'event' => new Resource($event),
        ];
    }
}
