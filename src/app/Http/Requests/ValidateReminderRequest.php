<?php

namespace LaravelEnso\Calendar\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateReminderRequest extends FormRequest
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
            'remind_at' => 'required|date',
        ];
    }
}
