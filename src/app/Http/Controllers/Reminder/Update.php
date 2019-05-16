<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Reminder;

use App\Http\Controllers\Controller;
use LaravelEnso\Calendar\app\Models\Reminder;
use LaravelEnso\Calendar\app\Http\Requests\ValidateReminderRequest;

class Update extends Controller
{
    public function __invoke(ValidateReminderRequest $request, Reminder $reminder)
    {
        return $reminder->update($request->validated());
    }
}
