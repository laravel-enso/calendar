<?php

use Faker\Generator as Faker;
use LaravelEnso\Calendar\app\Models\Reminder;

$factory->define(Reminder::class, function (Faker $faker) {
    return [
        'event_id' => $faker->numberBetween(1,10),
        'remind_at' => $faker->dateTime,
    ];
});
