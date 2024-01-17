<?php

namespace LaravelEnso\Calendar\Dynamics\Relations;

use Closure;
use LaravelEnso\Calendar\Models\Calendar;
use LaravelEnso\DynamicMethods\Contracts\Relation;
use LaravelEnso\Users\Models\User;

class Calendars implements Relation
{
    public function bindTo(): array
    {
        return [User::class];
    }

    public function name(): string
    {
        return 'calendars';
    }

    public function closure(): Closure
    {
        return fn (User $user) => $user
            ->hasMany(Calendar::class, 'created_by');
    }
}
