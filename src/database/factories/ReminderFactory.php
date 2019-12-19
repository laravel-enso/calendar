<?php

use Faker\Generator as Faker;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Models\Reminder;

$factory->define(Reminder::class, fn(Faker $faker) => [
    'event_id' => factory(Event::class)->create()->id,
    'scheduled_at' => $faker->dateTime,
]);
