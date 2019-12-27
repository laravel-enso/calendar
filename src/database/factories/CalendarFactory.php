<?php

use Faker\Generator as Faker;
use LaravelEnso\Calendar\app\Enums\Colors;
use LaravelEnso\Calendar\app\Models\Calendar;

$factory->define(Calendar::class, fn (Faker $faker) => [
    'name' => $faker->text,
    'color' => Colors::values()->random(),
    'private' => false,
]);
