<?php

namespace LaravelEnso\Calendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Calendar\Enums\Colors;

class ValidateCalendarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'color' => 'required|in:'.Colors::keys()->implode(','),
            'private' => 'required|boolean',
        ];
    }
}
