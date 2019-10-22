<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Event;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Facades\Calendars;
use LaravelEnso\Calendar\app\Http\Resources\Event as Resource;
use LaravelEnso\Calendar\app\Services\Request as CalendarRequest;

class Index extends Controller
{
    public function __invoke(Request $request)
    {
        $events = Calendars::all()
            ->intersectByKeys(collect($request->get('calendars'))->flip())
            ->map(function ($calendar) {
                return get_class($calendar);
            })
            ->values()->unique()
            ->reduce(function ($events, $calendar) use ($request) {
                return $events->concat(
                    $calendar::events(new CalendarRequest($request))
                );
            }, collect());

        return Resource::collection($events);
    }
}
