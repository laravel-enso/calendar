<?php

namespace LaravelEnso\Calendar\App\Forms\Builders;

use LaravelEnso\Calendar\App\Enums\Frequencies;
use LaravelEnso\Calendar\App\Http\Resources\Reminder;
use LaravelEnso\Calendar\App\Models\Event;
use LaravelEnso\Forms\App\Services\Form;

class EventForm
{
    protected const FormPath = __DIR__.'/../Templates/event.json';

    protected Form $form;

    public function __construct()
    {
        $this->form = (new Form(static::FormPath));
    }

    public function create()
    {
        return $this->form->actions('store')->create();
    }

    public function edit(Event $event)
    {
        return $this->form->value('attendees', $event->attendeeList())
            ->meta('recurrence_ends_at', 'hidden', $event->frequency === Frequencies::Once)
            ->value('reminders', Reminder::collection($event->reminders))
            ->actions([])
            ->edit($event);
    }
}
