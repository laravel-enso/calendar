<?php

namespace LaravelEnso\Calendar\app\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\Calendar\app\Models\Event;

class EventPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->belongsToAdminGroup()) {
            return true;
        }
    }

    public function handle($user, Event $event)
    {
        return is_null($user->person->company_id)
            || $user->person->company_id === $event->createdBy->person->company_id;
    }
}
