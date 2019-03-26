<?php

namespace LaravelEnso\Calendar\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\Calendar\app\Http\Requests\ValidateReminderRequest;
use LaravelEnso\Calendar\app\Models\Reminder;

class ReminderController extends Controller
{
    public function store(ValidateReminderRequest $request)
    {
        return Reminder::create($request->validated());
    }

    public function update(ValidateReminderRequest $request, Reminder $reminder)
    {
        return $reminder->update($request->validated());
    }

    public function destroy(Reminder $reminder)
    {
        $reminder->delete();

        return [
            'message' => __('The reminder was successfully deleted'),
        ];
    }
}
