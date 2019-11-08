<?php

namespace LaravelEnso\Calendar\app\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\Calendar\app\Contracts\Calendar as Contract;
use LaravelEnso\Calendar\app\Models\Calendar;

class CalendarPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->isAdmin() || $user->isSupervisor()) {
            return true;
        }
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
