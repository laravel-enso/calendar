<?php

use Carbon\Carbon;
use Faker\Generator as Faker;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Models\Calendar;
use LaravelEnso\Calendar\app\Models\Reminder;
use LaravelEnso\Calendar\app\Enums\Frequencies;

$factory->define(Event::class, function (Faker $faker) {
    $date = $faker->date(config('enso.config.dateFormat'));

    return [
        'parent_id' => null,
        'body' => $faker->text,
        'title' => $faker->title,
        'calendar_id' => factory(Calendar::class)->create()->id,
        'frequence' => Frequencies::Once,
        'start_date' => $date,
        'end_date' => $date,
        'start_time' => '12:00',
        'end_time' => '14:00',
        'recurrence_ends_at' => $faker->dateTime->format(config('enso.config.dateFormat')),
        'is_all_day' => false,
        'is_readonly' => false,
        'location' => $faker->city,
        'lat' => $faker->latitude,
        'lng' => $faker->longitude
    ];
});
