<?php

namespace LaravelEnso\Calendar\app\Policies;

use LaravelEnso\Calendar\app\Models\Event;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        return $user->belongsToAdminGroup();
    }

    public function handle($user, Event $event)
    {
        return $user->id === $event->created_by;
    }
}
