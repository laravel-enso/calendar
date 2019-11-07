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
            'start_date' => $this->requiredOrFilled().'|date',
            'end_date' => $this->requiredOrFilled().'|nullable|date|after_or_equal:start_date',
            'start_time' => $this->requiredOrFilled().'|date_format:H:i',
            'end_time' => $this->requiredOrFilled().'|nullable|date_format:H:i',
            'attendees.*' => 'exists:users,id',
            'recurrence_ends_at' => 'nullable',
            'is_all_day' => $this->requiredOrFilled().'|boolean',
            'updateType' => 'nullable|in:'.UpdateType::keys()->implode(','),
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->sometimes('end_time', 'after:start_time', function () {
            return $this->has(['start_date', 'end_date'])
                && $this->get('start_date') === $this->get('end_date');
        });

        $validator->sometimes(
            'recurrence_ends_at',
            'date|required|after_or_equal:start_date',
            function () {
                return $this->has('frequence')
                    && $this->get('frequence') !== Frequencies::Once;
            });

        if ($this->get('updateType') === UpdateType::OnlyThisEvent
            && $this->get('frequence') !== Frequencies::Once) {
            $validator->after(function ($validator) {
                $validator->errors()->add('frequence', __('Must be once'));
            });
        }
    }

    protected function requiredOrFilled()
    {
        return $this->method() === 'POST'
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
