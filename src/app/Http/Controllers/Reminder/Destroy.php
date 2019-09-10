<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Reminder;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Reminder;

class Destroy extends Controller
{
    public function __invoke(Reminder $reminder)
    {
        $reminder->delete();

        return ['message' => __('The reminder was successfully deleted')];
    }
}
