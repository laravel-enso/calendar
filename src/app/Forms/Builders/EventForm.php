<?php

namespace LaravelEnso\Calendar\app\Forms\Builders;

use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Enums\UpdateType;
use LaravelEnso\Calendar\app\Http\Resources\Reminder;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Forms\app\Services\Form;

class EventForm
{
    protected const FormPath = __DIR__.'/../Templates/event.json';

    protected $form;

    public function __construct()
    {
        $this->form = (new Form(static::FormPath))
            ->meta('start_date', 'format', $this->dateFormat())
            ->meta('end_date', 'format', $this->dateFormat())
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
            ->value('reminders', Reminder::collection($event->reminders))
            ->value('start_time', date('H:i', strtotime($event->start_time)))
            ->value('end_time', date('H:i', strtotime($event->end_time)))
            ->value('update_type', UpdateType::ThisAndFutureEvents)
            ->options('update_type', $this->updateTypeOptions($event))
            ->actions(['update'])
            ->edit($event);
    }

    private function dateFormat()
    {
        return config('enso.config.dateFormat');
    }

    private function dateTimeFormat(): string
    {
        return $this->dateFormat().' H:i';
    }

    protected function updateTypeOptions(Event $event)
    {
        return $event->parent_id
            ? UpdateType::select()
            : UpdateType::forParent();
    }
}
