<?php

namespace LaravelEnso\Calendar\app\Http\Requests;

class ValidateEventUpdate extends ValidateEventStore
{
    public function rules()
    {
        return [
            'ends_at' => 'filled:is_all_day,true|nullable|date|after:starts_at',
            'ends_time_at' => 'nullable|date_format:H:i',
        ] + parent::rules();
    }

    protected function genericRule()
    {
        return 'filled';
    }
}
