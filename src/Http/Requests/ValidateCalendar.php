<?php

namespace LaravelEnso\Calendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LaravelEnso\Calendar\Enums\Colors;

class ValidateCalendar extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'color' => Rule::Enum(Colors::class),
            'private' => 'required|boolean',
        ];
    }
}
