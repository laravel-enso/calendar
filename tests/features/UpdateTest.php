<?php

namespace LaravelEnso\Calendar\tests\features;

use LaravelEnso\Calendar\App\Models\Event;
use LaravelEnso\Calendar\App\Enums\UpdateType;
use LaravelEnso\Calendar\App\Enums\Frequencies;

class UpdateTest extends BaseTest
{
    /** @test */
    public function can_update_all_events()
    {
        $this->parameters = ['end_time' => '22:20'];

        $this->create()->update(3, UpdateType::All);

        $this->assertCount($this->count, Event::where($this->parameters)->get());
    }

    /** @test */
    public function can_update_future_events()
    {
        $this->parameters = ['end_time' => '22:20'];

        $this->create()->update(3, UpdateType::ThisAndFutureEvents);

        $this->assertParents([null, 1, null, 3, 3]);

        $this->assertCount($this->count - 2, Event::where($this->parameters)->get());
    }

    /** @test */
    public function can_update_future_date_events()
    {
        $this->parameters = [
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addDay()->format('Y-m-d'),
        ];

        $this->create()->update(3, UpdateType::ThisAndFutureEvents);

        $this->assertParents([null, 1, null, 3, 3, 3]);
        $this->assertStartDates([0, 1, 1, 2, 3, 4]);
        $this->assertDate(now()->addDay(), Event::first()->recurrence_ends_at);
    }

    /** @test */
    public function can_update_all_date_events()
    {
        $this->parameters = [
            'start_date' => now()->addDay()->format('Y-m-d'),
            'end_date' => now()->addDay()->format('Y-m-d'),
        ];

        $this->create()->update(3, UpdateType::All);

        $this->assertParents([null, 1, 1, 1, 1, 1]);
        $this->assertStartDates([-1, 0, 1, 2, 3, 4]);
    }

    /** @test */
    public function can_update_increase_recurrence_ends_at_events()
    {
        $this->parameters = ['recurrence_ends_at' => now()->addDays(7)->format('Y-m-d')];

        $this->create()->update(1, UpdateType::All);

        $this->assertParents([null, 1, 1, 1, 1, 1, 1, 1]);
        $this->assertStartDates(range(0, 7));
    }

    /** @test */
    public function can_update_decrease_recurrence_ends_at_events()
    {
        $this->parameters = [
            'recurrence_ends_at' => $this->date->clone()->addDays(2)->format('Y-m-d'),
        ];

        $this->create()->update(1, UpdateType::All);

        $this->assertParents([null, 1, 1]);
    }

    /** @test */
    public function can_update_frequency_to_once_on_event()
    {
        $this->parameters = ['frequency' => Frequencies::Once];

        $this->create()->update(1, UpdateType::All);

        $this->assertParents([null]);
    }

    /** @test */
    public function can_update_frequency_from_once_on_event()
    {
        $this->event->frequency = Frequencies::Once;

        $this->parameters = [
            'frequency' => Frequencies::Daily,
            'recurrence_ends_at' => $this->date->clone()->addDays(4)->format('Y-m-d'),
        ];

        $this->create()->update(1, UpdateType::OnlyThisEvent);

        $this->assertParents([null, 1, 1, 1, 1]);
        $this->assertStartDates(range(0, 4));
    }

    /** @test */
    public function can_update_single_event()
    {
        $this->parameters = ['end_time' => '20:20'];

        $event = $this->create()->update(3, UpdateType::OnlyThisEvent);

        $this->assertParents([null, 1, null, null, 4]);

        $this->assertDate(now()->addDay(), Event::first()->recurrence_ends_at);

        $this->assertEquals(Frequencies::Once, $event->frequency);
        $this->assertCount(1, Event::where($this->parameters)->get());
    }

    /** @test */
    public function can_update_non_frequent_event()
    {
        $this->event->frequency = Frequencies::Once;
        $this->parameters = ['end_time' => '20:20'];

        $this->create()->update(1, UpdateType::OnlyThisEvent);

        $this->assertCount(1, Event::where($this->parameters)->get());
    }

    /** @test */
    public function can_update_single_parent_event()
    {
        $this->parameters = ['end_time' => '20:20'];

        $this->create()->update(1, UpdateType::OnlyThisEvent);

        $this->assertParents([null, null, 2, 2, 2]);
        $this->assertCount(1, Event::where($this->parameters)->get());
    }

    /** @test */
    public function can_update_parent_event()
    {
        $this->parameters = ['end_time' => '20:20'];

        $this->create()->update(1, UpdateType::ThisAndFutureEvents);

        $this->assertParents([null, 1, 1, 1, 1]);
        $this->assertCount($this->count, Event::where($this->parameters)->get());
    }

    /** @test */
    public function can_update_last_single_event()
    {
        $this->parameters = ['end_time' => '20:20'];

        $this->create()->update($this->count, UpdateType::OnlyThisEvent);

        $this->assertParents([null, 1, 1, 1, null]);
        $this->assertCount(1, Event::where($this->parameters)->get());
        $this->assertDate(Event::find(4)->start_date, Event::first()->recurrence_ends_at);
    }

    /** @test */
    public function can_update_last_all_event()
    {
        $this->parameters = ['end_time' => '20:20'];

        $this->create()->update($this->count, UpdateType::All);

        $this->assertParents([null, 1, 1, 1, 1]);
        $this->assertCount($this->count, Event::where($this->parameters)->get());
    }
}
