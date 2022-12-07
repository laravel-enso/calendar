<?php

namespace LaravelEnso\Calendar\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelEnso\Calendar\Enums\Color;
use LaravelEnso\Calendar\Models\Calendar;

class CalendarFactory extends Factory
{
    protected $model = Calendar::class;

    public function definition()
    {
        return [
            'name'    => $this->faker->text,
            'color'   => Color::random(),
            'private' => false,
        ];
    }
}
