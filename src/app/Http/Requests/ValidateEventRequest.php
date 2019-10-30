<?php

namespace LaravelEnso\Calendar\app\Http\Requests;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Calendar\app\Models\Calendar;
use LaravelEnso\Calendar\app\Enums\UpdateType;
use LaravelEnso\Calendar\app\Enums\Frequencies;

class ValidateEventRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => $this->requiredOrFilled(),
            'body' => 'nullable',
            'calendar_id' => $this->requiredOrFilled().'|in:'.Calendar::pluck('id')->implode(','),
            'frequence' => $this->requiredOrFilled().'|in:'.Frequencies::keys()->implode(','),
            'location' => 'nullable',
            'lat' => 'nullable',
            'lng' => 'nullable',
            'starts_date' => $this->requiredOrFilled().'|date',
            'ends_date' => $this->requiredOrFilled().'|nullable|date|after_or_equal:starts_date',
            'starts_time' => $this->requiredOrFilled().'|date_format:H:i',
            'ends_time' => $this->requiredOrFilled().'|nullable|date_format:H:i',
            'attendees.*' => 'exists:users,id',
            'recurrence_ends_at' => 'nullable',
            'is_all_day' => $this->requiredOrFilled().'|boolean',
            'is_readonly' => $this->requiredOrFilled().'|boolean',
            'update_type' => 'nullable|in:'.UpdateType::keys()->implode(','),
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('ends_time', 'after:starts_time', function () {
            return $this->has(['starts_date', 'ends_date'])
                && $this->get('starts_date') === $this->get('ends_date');
        });

        $validator->sometimes('recurrence_ends_at', 'date|required|after:starts_date', function () {
            return $this->has('frequence')
                && $this->get('frequence') !== Frequencies::Once;
        });

        if ($this->filled('is_readonly') && $this->get('is_readonly') !== false) {
            $validator->after(function ($validator) {
                $validator->errors()->add('is_readonly', __('Must be false'));
            });
        }
    }

    protected function requiredOrFilled()
    {
        return $this->getMethod() === 'POST'
            ? 'required'
            : 'filled';
    }

    public function reminders()
    {
        return collect($this->get('reminders'))
            ->reject(function ($reminder) {
                return empty($reminder['scheduled_at']);
            });
    }
}
