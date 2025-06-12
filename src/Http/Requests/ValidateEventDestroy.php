<?php

namespace LaravelEnso\Calendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Calendar\Enums\Frequency;
use LaravelEnso\Calendar\Enums\UpdateType;

class ValidateEventDestroy extends FormRequest
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
            Rule::enum(UpdateType::class),
            fn () => $this->route('event')->frequency !== Frequency::Once->value
        );
    }
}
