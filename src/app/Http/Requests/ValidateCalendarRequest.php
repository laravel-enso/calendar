<?php

namespace LaravelEnso\Calendar\app\Http\Requests;

use LaravelEnso\Calendar\app\Enums\Colors;
use Illuminate\Foundation\Http\FormRequest;

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
        ];
    }
}
