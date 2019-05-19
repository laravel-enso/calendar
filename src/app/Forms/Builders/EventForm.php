<?php

namespace LaravelEnso\Calendar\app\Forms\Builders;

use LaravelEnso\Forms\app\Services\Form;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\Frequencies;

class EventForm
{
    private const FormPath = __DIR__.'/../Templates/event.json';

    private $form;

    public function __construct()
    {
        $this->format = config('enso.config.dateFormat').' H:i';

        $this->form = (new Form(self::FormPath))
            ->meta('starts_at', 'format', $this->format)
            ->meta('ends_at', 'format', $this->format)
            ->meta('recurrence_ends_at', 'format', $this->format)
            ->meta('reminders', 'format', $this->format);
    }

    public function create()
    {
        return $this->form->actions('store')
            ->create();
    }

    public function edit(Event $event)
    {
        if ($event->is_readonly) {
            $this->form->actions(['create']);
        }

        return $this->form->value('attendees', $event->attendeeList())
            ->meta('recurrence_ends_at', 'hidden', $event->frequence === Frequencies::Once)
            ->value('reminders', $this->reminders($event))
            ->actions(['destroy', 'create', 'update'])
            ->edit($event);
    }

    private function reminders($event)
    {
        return $event->reminders->map(function ($reminder) {
            $remind_at = $reminder->remind_at->format($this->format);
            $item = $reminder->toArray();
            $item['remind_at'] = $remind_at;

            return $item;
        });
    }
}
