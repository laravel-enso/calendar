<?php

namespace LaravelEnso\Calendar\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Calendar\app\Facades\Calendars;

class ValidateEventIndexRequest extends FormRequest
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
