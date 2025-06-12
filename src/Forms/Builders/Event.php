<?php

namespace LaravelEnso\Calendar\Forms\Builders;

use LaravelEnso\Calendar\Enums\Frequency;
use LaravelEnso\Calendar\Http\Resources\Reminder;
use LaravelEnso\Calendar\Models\Event as Model;
use LaravelEnso\Forms\Services\Form;

class Event
{
    private const TemplatePath = __DIR__.'/../Templates/event.json';

    protected Form $form;

    public function __construct()
    {
        $this->form = (new Form($this->templatePath()));
    }

    public function create()
    {
        return $this->form->actions('store')->create();
    }

    public function edit(Model $event)
    {
        return $this->form->value('attendees', $event->attendeeList())
            ->meta('recurrence_ends_at', 'hidden', $event->frequency === Frequency::Once->value)
            ->value('reminders', Reminder::collection($event->reminders))
            ->actions([])
            ->edit($event);
    }

    protected function templatePath(): string
    {
        return self::TemplatePath;
    }
}
