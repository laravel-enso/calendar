<?php

namespace LaravelEnso\Calendar\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Facades\Calendars;

class ValidateEventStore extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => $this->genericRule(),
            'body' => 'nullable',
            'calendar_id' => $this->genericRule().'|in:'.Calendars::all()->keys()->implode(','),
            'frequence' => $this->genericRule().'|in:'.Frequencies::keys()->implode(','),
            'location' => 'nullable',
            'lat' => 'nullable',
            'lng' => 'nullable',
            'starts_at' => $this->genericRule().'|date',
            'ends_at' => 'required_unless:is_all_day,true|nullable|date|after:starts_at',
            'recurrence_ends_at' => $this->has('frequence') && $this->get('frequence') !== Frequencies::Once
                ? 'date|required|after:starts_at'
                : 'nullable',
            'is_all_day' => $this->genericRule().'|boolean',
            'is_readonly' => $this->genericRule().'|boolean',
        ];
    }

    public function withValidator($validator)
    {
        if ($this->filled('is_readonly') && $this->get('is_readonly') !== false) {
            $validator->after(function ($validator) {
                $validator->errors()->add('is_readonly', __('Must be false'));
            });
        }
    }

    protected function genericRule()
    {
        return 'required';
    }

    public function reminders()
    {
        return collect($this->get('reminders'))
            ->reject(function ($reminder) {
                return empty($reminder['remind_at']);
            });
    }
}
