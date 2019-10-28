<?php

namespace LaravelEnso\Calendar\app\Http\Requests;

use LaravelEnso\Calendar\app\Enums\UpdateType;

class ValidateEventUpdate extends ValidateEventStore
{
    public function rules()
    {
        return [
            'ends_at' => 'filled:is_all_day,true|nullable|date|after:starts_at',
            'ends_time_at' => 'nullable|date_format:H:i',
            'update_type' => 'nullable|in:'.UpdateType::keys()->implode(','),
        ] + parent::rules();
    }

    protected function genericRule()
    {
        return 'filled';
    }
}
