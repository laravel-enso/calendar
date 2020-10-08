<?php

namespace LaravelEnso\Calendar\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelEnso\Calendar\Enums\Frequencies;
use LaravelEnso\Calendar\Models\Calendar;
use LaravelEnso\Calendar\Models\Event;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        return [
            'parent_id' => null,
            'body' => $this->faker->text,
            'title' => $this->faker->title,
            'calendar_id' => Calendar::factory(),
            'frequency' => Frequencies::Once,
            'start_date' => $this->faker->date('Y-m-d'),
            'end_date' => $this->faker->date('Y-m-d'),
            'start_time' => '12:00',
            'end_time' => '14:00',
            'recurrence_ends_at' => null,
            'is_all_day' => false,
            'location' => $this->faker->city,
            'lat' => $this->faker->latitude,
            'lng' => $this->faker->longitude,
        ];
    }
}
