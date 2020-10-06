<?php

namespace LaravelEnso\Calendar\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelEnso\Calendar\Models\Event;
use LaravelEnso\Calendar\Models\Reminder;

class ReminderFactory extends Factory
{
    protected $model = Reminder::class;

    public function definition()
    {
        return [
            'event_id' => fn () => Event::factory()->create()->id,
            'scheduled_at' => $this->faker->dateTime,
        ];
    }
}
