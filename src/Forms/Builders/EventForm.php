<?php

namespace LaravelEnso\Calendar\Forms\Builders;

use LaravelEnso\Calendar\Http\Resources\Reminder;
use LaravelEnso\Calendar\Models\Event;
use LaravelEnso\Forms\Services\Form;

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
            ->meta('recurrence_ends_at', 'hidden', $event->isOnce())
            ->value('reminders', Reminder::collection($event->reminders))
            ->actions([])
            ->edit($event);
    }
}
