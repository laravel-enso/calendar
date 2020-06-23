<?php

namespace LaravelEnso\Calendar\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Validator;
use LaravelEnso\Calendar\Enums\Frequencies;
use LaravelEnso\Calendar\Enums\UpdateType;
use LaravelEnso\Calendar\Models\Calendar;

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
            'frequency' => $this->requiredOrFilled().'|in:'.Frequencies::keys()->implode(','),
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
        $this->validateFrequency($validator);
        $this->validateEndTime($validator);
        $this->validateEndsAt($validator);
        $this->validateRecurrenceEndsAt($validator);

        $validator->after(fn ($validator) => $this->after($validator));
    }

    public function reminders()
    {
        return (new Collection($this->get('reminders')))
            ->reject(fn ($reminder) => ! $reminder['scheduled_at']);
    }

    private function validateFrequency($validator)
    {
        $validator->sometimes(
            'frequency',
            'not_in:'.Frequencies::Once,
            fn () => $this->filled('updateType')
                && $this->get('updateType') !== UpdateType::OnlyThis
        );
    }

    private function validateEndTime($validator)
    {
        $validator->sometimes(
            'end_time',
            'after:start_time',
            fn () => $this->has(['start_date', 'end_date'])
                && $this->get('start_date') === $this->get('end_date')
        );
    }

    private function validateEndsAt($validator)
    {
        $validator->sometimes(
            'recurrence_ends_at',
            'date|required|after_or_equal:start_date',
            fn () => $this->has('frequency')
                && $this->get('frequency') !== Frequencies::Once
        );
    }

    private function validateRecurrenceEndsAt($validator)
    {
        $validator->sometimes(
            'recurrence_ends_at',
            'date|required|after_or_equal:start_date',
            fn () => $this->has('frequency')
                && $this->get('frequency') !== Frequencies::Once
        );
    }

    private function after($validator)
    {
        if ($this->has('start_date') && $this->predatesSubsequence()) {
            $validator->errors()
                ->add('start_date', "You can't predate a subsequence of events");
        }

        if ($this->oneWithRecurrence()) {
            $validator->errors()
                ->add('recurrence_ends_at', "You can't have recurrence on singular events");
        }
    }

    private function predatesSubsequence(): bool
    {
        return $this->filled('updateType') && (int) $this->get('updateType') !== UpdateType::OnlyThis
            && optional($this->route('event'))->parent_id !== null
            && Carbon::parse($this->get('start_date'))->lt($this->route('event')->start_date);
    }

    private function requiredOrFilled(): string
    {
        return $this->method() === 'POST'
            ? 'required'
            : 'filled';
    }

    private function oneWithRecurrence()
    {
        return $this->get('frequency') === Frequencies::Once
            && $this->filled('recurrence_ends_at');
    }
}
