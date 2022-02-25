<?php

namespace LaravelEnso\Calendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Calendar\Facades\Calendars;

class ValidateEventIndex extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date',
            'calendars' => 'array|in:'.Calendars::keys()->implode(','),
        ];
    }
}
