<?php

namespace LaravelEnso\Calendar\app\Forms\Builders;

use LaravelEnso\Forms\app\Services\Form;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\UpdateType;
use LaravelEnso\Calendar\app\Enums\Frequencies;

class EventForm
{
    protected const FormPath = __DIR__.'/../Templates/event.json';

    protected $form;

    public function __construct()
    {
        $this->form = (new Form(static::FormPath))
            ->meta('starts_on', 'format', $this->dateFormat())
            ->meta('ends_on', 'format', $this->dateFormat())
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
            ->meta('update_type', 'hidden', $event->frequence === Frequencies::Once)
            ->value('reminders', $this->reminders($event))
            ->value('update_type', UpdateType::All)
            ->value('starts_time', date('H:i', strtotime($event->starts_time)))
            ->value('ends_time', date('H:i', strtotime($event->ends_time)))
            ->value('update_type', UpdateType::Single)
            ->actions(['update'])
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
