<?php

namespace LaravelEnso\Calendar\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Calendar\app\Enums\Calendars;
use LaravelEnso\Calendar\app\Enums\Frequencies;

class ValidateEventUpdate extends ValidateEventStore
{
    protected function genericRule()
    {
        return 'filled';
    }
}
