<?php

namespace LaravelEnso\Calendar\app\Http\Requests;

class ValidateEventUpdate extends ValidateEventStore
{
    protected function genericRule()
    {
        return 'filled';
    }
}
