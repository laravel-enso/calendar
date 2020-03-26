<?php

namespace LaravelEnso\Calendar\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\Calendar\App\Models\Event;

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
