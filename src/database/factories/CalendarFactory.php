<?php

use Carbon\Carbon;
use Faker\Generator as Faker;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Models\Reminder;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use \LaravelEnso\Calendar\app\Enums\Colors;
use \LaravelEnso\Calendar\app\Models\Calendar;

$factory->define(Calendar::class, function (Faker $faker) {
    return [
        'name' => $faker->text,
        'color' => Colors::values()->random(),
        'private' => false,
    ];
});
