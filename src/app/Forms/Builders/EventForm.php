<?php

namespace LaravelEnso\Calendar\app\Forms\Builders;

use LaravelEnso\Forms\app\Services\Form;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Http\Resources\Reminder;

class EventForm
{
    protected const FormPath = __DIR__.'/../Templates/event.json';

    protected $form;

    public function __construct()
    {
        $this->form = (new Form(static::FormPath));
    }

    public function create()
    {
        return $this->form->actions('store')
            ->create();
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
