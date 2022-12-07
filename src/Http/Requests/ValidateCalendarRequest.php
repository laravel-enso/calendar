<?php

namespace LaravelEnso\Calendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use LaravelEnso\Calendar\Enums\Color;

class ValidateCalendarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'    => 'required',
            'color'   => ['required', new Enum(Color::class)],
            'private' => 'required|boolean',
        ];
    }
}
