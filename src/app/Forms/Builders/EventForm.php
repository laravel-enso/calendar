<?php

namespace LaravelEnso\Calendar\app\Forms\Builders;

use LaravelEnso\Forms\app\Services\Form;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\Frequencies;

class EventForm
{
    protected const FormPath = __DIR__.'/../Templates/event.json';

    protected $form;

    public function __construct()
    {
        $this->form = (new Form(static::FormPath))
            ->meta('starts_at', 'format', $this->dateTimeFormat())
            ->meta('ends_at', 'format', $this->dateTimeFormat())
            ->meta('recurrence_ends_at', 'format', $this->dateFormat())
            ->meta('reminders', 'format', $this->dateTimeFormat());
    }

    public function create()
    {
        return $this->form->actions('store')
            ->create();
    }

    public function edit(Event $event)
    {
        return $this->form->value('attendees', $event->attendeeList())
            ->meta('recurrence_ends_at', 'hidden', $event->frequence === Frequencies::Once)
            ->value('reminders', $this->reminders($event))
            ->actions(['destroy', 'update'])
            ->edit($event);
    }

    private function reminders($event)
    {
        return $event->reminders->map(function ($reminder) {
            $remindAt = $reminder->scheduled_at
                ->format($this->dateTimeFormat());

            return ['scheduled_at' => $remindAt] +
                $reminder->toArray();
        });
    }

    private function dateFormat()
    {
        return config('enso.config.dateFormat');
    }

    private function dateTimeFormat(): string
    {
        return $this->dateFormat().' H:i';
    }
}
