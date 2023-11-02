<?php

namespace LaravelEnso\Calendar\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\Calendar\Contracts\Calendar as Contract;
use LaravelEnso\Calendar\Models\Calendar;

class CalendarPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        return $user->isSuperior();
    }

    public function access($user, Contract $calendar)
    {
        return ! $calendar->private()
            || ($calendar instanceof Calendar
                && $user->id === $calendar->created_by);
    }

    public function handle($user, Calendar $calendar)
    {
        return $user->id === $calendar->created_by;
    }
}
