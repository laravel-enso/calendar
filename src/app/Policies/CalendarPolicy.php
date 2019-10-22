<?php

namespace LaravelEnso\Calendar\app\Policies;

use LaravelEnso\Calendar\app\Models\Calendar;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalendarPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        return $user->belongsToAdminGroup();
    }

    public function handle($user, Calendar $calendar)
    {
        return $user->id === $calendar->created_by;
    }
}
