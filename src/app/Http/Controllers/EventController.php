<?php

namespace LaravelEnso\Calendar\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelEnso\Calendar\app\Forms\Builders\EventForm;
use LaravelEnso\Calendar\app\Http\Requests\ValidateEventRequest;
use LaravelEnso\Calendar\app\Http\Resources\Event as Resource;
use LaravelEnso\Calendar\app\Http\Responses\Events;
use LaravelEnso\Calendar\app\Models\Event;

class EventController extends Controller
{
    public function index(Request $request)
    {
        return Resource::collection(
            (new Events($request))->get()
        );
    }

    public function create(EventForm $form)
    {
        return ['form' => $form->create()];
    }

    public function store(ValidateEventRequest $request)
    {
        $event = Event::create($request->validated());

        if (!empty($request->get('reminders'))) {
            $event->reminders()->createMany(
                collect($request->get('reminders'))
                    ->filter(function ($reminder) {
                        return !empty($reminder['remind_at']);
                    })->toArray()
            );
        }

        return [
            'message' => __('The event was created!'),
            'event'   => new Resource($event),
        ];
    }

    public function edit(Event $event, EventForm $form)
    {
        $this->authorize('handle', $event);

        return ['form' => $form->edit($event)];
    }

    public function update(ValidateEventRequest $request, Event $event)
    {
        $this->authorize('handle', $event);

        tap($event)->update($request->validated())
            ->updateReminders($request->get('reminders'));

        return [
            'message' => __('The event was updated!'),
            'event'   => new Resource($event),
        ];
    }

    public function destroy(Event $event)
    {
        $this->authorize('handle', $event);

        $event->delete();

        return [
            'message' => __('The event was successfully deleted'),
        ];
    }
}
