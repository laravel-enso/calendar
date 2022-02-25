<?php

namespace LaravelEnso\Calendar\Forms\Builders;

use LaravelEnso\Calendar\Models\Calendar as Model;
use LaravelEnso\Forms\Services\Form;

class Calendar
{
    private const TemplatePath = __DIR__.'/../Templates/calendar.json';

    protected Form $form;

    public function __construct()
    {
        $this->form = (new Form($this->templatePath()));
    }

    public function create()
    {
        return $this->form->actions('store')->create();
    }

    public function edit(Model $calendar)
    {
        return $this->form->actions(['destroy', 'update'])
            ->edit($calendar);
    }

    protected function templatePath(): string
    {
        return self::TemplatePath;
    }
}
