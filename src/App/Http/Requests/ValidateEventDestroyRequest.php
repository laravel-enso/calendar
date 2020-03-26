<?php

namespace LaravelEnso\Calendar\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Calendar\App\Enums\Frequencies;
use LaravelEnso\Calendar\App\Enums\UpdateType;

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
            'required|in:'.UpdateType::keys()->implode(','),
            fn () => $this->route('event')->frequency !== Frequencies::Once
        );
    }
}
