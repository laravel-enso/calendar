<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\UpdateType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LaravelEnso\Calendar\app\Http\Resources\Event as Resource;
use LaravelEnso\Calendar\app\Http\Requests\ValidateEventRequest;

class Update extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateEventRequest $request, Event $event)
    {
        $this->authorize('handle', $event);

        $event->updateEvent(
            $request->validated(),
            $request->get('updateType', UpdateType::OnlyThisEvent)
        )->updateReminders($request->reminders())
        ->syncAttendees($request->get('attendees'));

        return [
            'message' => __('The event was updated!'),
            'event' => new Resource($event),
        ];
    }
}
