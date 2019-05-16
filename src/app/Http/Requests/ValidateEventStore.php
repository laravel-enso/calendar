<?php

namespace LaravelEnso\Calendar\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Calendar\app\Enums\Calendars;
use LaravelEnso\Calendar\app\Enums\Frequencies;

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
            'calendar' => $this->genericRule().'|in:'.Calendars::keys()->implode(','),
            'frequence' => $this->genericRule().'|in:'.Frequencies::keys()->implode(','),
            'location' => 'nullable',
            'lat' => 'nullable',
            'lng' => 'nullable',
            'starts_at' => 'required|date',
            'ends_at' => 'required_unless:is_all_day,true|nullable|date|after:starts_at',
            'frequence_ends_at' => $this->has('frequence') && $this->get('frequence') !== Frequencies::Once
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
}
