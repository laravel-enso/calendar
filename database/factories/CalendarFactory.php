<?php

namespace LaravelEnso\Calendar\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelEnso\Calendar\Enums\Colors;
use LaravelEnso\Calendar\Models\Calendar;

class CalendarFactory extends Factory
{
    protected $model = Calendar::class;

    public function definition()
    {
        return [
            'name' => $this->faker->text,
            'color' => Colors::values()->random(),
            'private' => false,
        ];
    }
}
