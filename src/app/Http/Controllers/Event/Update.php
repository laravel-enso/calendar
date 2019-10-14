<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Http\Resources\Event as Resource;
use LaravelEnso\Calendar\app\Http\Requests\ValidateEventUpdate;

class Update extends Controller
{
    public function __invoke(ValidateEventUpdate $request, Event $event)
    {
        //$this->authorize('handle', $event);

        //tap($event)->update($request->validated())
        //    ->updateReminders($request->get('reminders'));

        return [
            'message' => __('The event was updated!'),
            'event' => new Resource($event),
        ];
    }
}
