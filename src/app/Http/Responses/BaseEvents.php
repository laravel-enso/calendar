<?php

namespace LaravelEnso\Calendar\app\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Contracts\ResolvesEvents;

class BaseEvents implements ResolvesEvents
{
    public function getEvents(Request $request): Collection
    {
        return Event::allowed()
            ->with(['attendees', 'reminders'])
            ->whereCalendar($request->get('calendar'))
            ->between($request->get('startDate'), $request->get('endDate'))
            ->get();
    }
}
