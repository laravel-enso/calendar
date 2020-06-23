<?php

namespace LaravelEnso\Calendar\Forms\Builders;

use LaravelEnso\Calendar\Models\Calendar;
use LaravelEnso\Forms\Services\Form;

class CalendarForm
{
    protected const FormPath = __DIR__.'/../Templates/calendar.json';

    protected Form $form;

    public function __construct()
    {
        $this->form = (new Form(static::FormPath));
    }

    public function create()
    {
        return $this->form->actions('store')->create();
    }

    public function edit(Calendar $calendar)
    {
        return $this->form->actions(['destroy', 'create', 'update'])
            ->edit($calendar);
    }
}
