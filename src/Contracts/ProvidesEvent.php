<?php

namespace LaravelEnso\Calendar\Contracts;

use Carbon\Carbon;
use LaravelEnso\Calendar\Enums\Frequency;

interface ProvidesEvent
{
    public function getKey();

    public function title(): string;

    public function body(): ?string;

    public function start(): Carbon;

    public function end(): Carbon;

    public function location(): ?string;

    public function getCalendar(): Calendar;

    public function frequency(): Frequency;

    public function recurrenceEnds(): ?Carbon;

    public function allDay(): bool;

    public function readonly(): bool;
}
