<?php

namespace LaravelEnso\Calendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use LaravelEnso\Calendar\Enums\Frequency;
use LaravelEnso\Calendar\Enums\UpdateType;

class ValidateEventDestroyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->sometimes(
            'updateType',
            ['required', new Enum(UpdateType::class)],
            fn () => $this->route('event')->frequency !== Frequency::Once
        );
    }
}
