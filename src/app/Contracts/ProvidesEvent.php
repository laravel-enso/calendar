<?php

namespace LaravelEnso\Calendar\app\Contracts;

use Carbon\Carbon;

interface ProvidesEvent
{
    public function getKey();

    public function title();

    public function body();

    public function start(): Carbon;

    public function end(): Carbon;

    public function location();

    public function calendar();

    public function frequence();

    public function recurrenceEnds(): ?Carbon;

    public function allDay();

    public function readonly();
}
