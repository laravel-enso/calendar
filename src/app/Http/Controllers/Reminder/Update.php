<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Reminder;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Reminder;
use LaravelEnso\Calendar\app\Http\Requests\ValidateReminderRequest;

class Update extends Controller
{
    public function __invoke(ValidateReminderRequest $request, Reminder $reminder)
    {
        return $reminder->update($request->validated());
    }
}
