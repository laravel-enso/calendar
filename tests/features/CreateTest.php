<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\Calendar\Enums\Frequency;
use LaravelEnso\Calendar\Models\Event;
use LaravelEnso\Users\Models\User;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    protected Event $event;
    protected Carbon $date;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed()
            ->actingAs(User::first());

        $this->date = Carbon::today();

        $this->event = Event::factory()->make();
    }

    /** @test */
    public function create_event()
    {
        $this->post(route('core.calendar.events.store'), [
            'start_date' => $this->date->format('Y-m-d'),
            'end_date' => $this->date->format('Y-m-d'),
            'frequency' => Frequency::Once->value,
        ] + $this->event->toArray());

        $this->assertTrue(Event::exists());
    }

    /** @test */
    public function create_sequence()
    {
        $count = 5;
        $recurrenceEndsAt = $this->date->clone()->addDays($count - 1);

        $this->post(route('core.calendar.events.store'), [
            'start_date' => $this->date->format('Y-m-d'),
            'end_date' => $this->date->format('Y-m-d'),
            'frequency' => Frequency::Daily->value,
            'recurrence_ends_at' => $recurrenceEndsAt->format('Y-m-d'),
        ] + $this->event->toArray());

        $events = Event::all();

        $this->assertCount($count, $events);
        $this->assertCount($count - 1, $events->first()->events);

        $events->each(function ($event, $index) use ($recurrenceEndsAt) {
            $this->assertTrue(
                $event->start_date->eq($this->date->clone()->addDays($index))
            );

            $this->assertTrue(
                $event->recurrence_ends_at->eq($recurrenceEndsAt)
            );
        });
    }
}
