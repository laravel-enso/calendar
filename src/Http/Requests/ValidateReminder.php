<?php

namespace LaravelEnso\Calendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateReminder extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date',
        ];
    }
}
