<?php

use LaravelEnso\Calendar\Calendars\BirthdayCalendar;
use LaravelEnso\Roles\Enums\Roles;

return [
    'roles' => [
        BirthdayCalendar::class => Roles::keys()
    ]
];
