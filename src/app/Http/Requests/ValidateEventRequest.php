<?php

namespace LaravelEnso\Calendar\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Calendar\app\Enums\Calendars;
use LaravelEnso\Calendar\app\Enums\Frequencies;

class ValidateEventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $required = $this->method() === 'post'
            ? 'required'
            : 'filled';

        return [
            'title'             => $required,
            'body'              => 'nullable',
            'calendar'          => $required.'|in:'.Calendars::keys()->implode(','),
            'frequence'         => $required.'|in:'.Frequencies::keys()->implode(','),
            'location'          => 'nullable',
            'lat'               => 'nullable',
            'lng'               => 'nullable',
            'starts_at'         => 'required|date',
            'ends_at'           => 'required_unless:is_all_day,true|nullable|date|after:starts_at',
            'frequence_ends_at' => $this->has('frequence') && $this->get('frequence') !== Frequencies::Once
                ? 'date|required|after:starts_at'
                : 'nullable',
            'is_all_day'  => $required.'|boolean',
            'is_readonly' => $required.'|boolean',
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
}
