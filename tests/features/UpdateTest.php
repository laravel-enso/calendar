<?php

namespace LaravelEnso\Calendars\tests\features;

use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\UpdateType;
use LaravelEnso\Calendar\app\Enums\Frequencies;

class UpdateTest extends BaseTest
{
    /** @test */
    public function can_update_all_events()
    {
        $this->parameters = ['end_time' => '22:20'];

        $this->create()->update(3, UpdateType::All);

        $this->assertCount($this->interval, Event::where($this->parameters)->get());
    }

    /** @test */
    public function can_update_future_events()
    {
        $this->parameters = ['end_time' => '22:20'];

        $this->create()->update(3, UpdateType::Futures);

        $this->assertParents([null, 1, null, 3, 3]);
        $this->assertCount($this->interval - 2, Event::where($this->parameters)->get());
    }

    /** @test */
    public function can_update_future_date_events()
    {
        $this->parameters = [
            'start_date' => now()->addDay(),
            'end_date' => now()->addDay(),
        ];

        $this->create()->update(3, UpdateType::Futures);

        $this->assertParents([null, 1, null, 3, 3, 3]);
        $this->assertStartDates([0, 1, 1, 2, 3, 4]);
    }

    /** @test */
    public function can_update_all_date_events()
    {
        $this->parameters = [
            'start_date' => now()->addDay(),
            'end_date' => now()->addDay(),
        ];

        $this->create()->update(3, UpdateType::All);

        $this->assertParents([null, 1, 1, 1, 1, 1]);
        $this->assertStartDates([-1, 0, 1, 2, 3, 4]);
    }

    /** @test */
    public function can_update_increase_recurrence_ends_at_events()
    {
        $this->parameters = ['recurrence_ends_at' => now()->addDays(7)];

        $this->create()->update(1, UpdateType::All);

        $this->assertParents([null, 1, 1, 1, 1, 1, 1, 1]);
        $this->assertEvents($this->date, now()->addDays(7));
    }

    /** @test */
    public function can_update_decrease_recurrence_ends_at_events()
    {
        $this->parameters = [
            'recurrence_ends_at' => $this->date->clone()->addDays(2),
        ];

        $this->create()->update(1, UpdateType::All);

        $this->assertParents([null, 1, 1]);
    }

    /** @test */
    public function can_update_frequency_to_once_on_event()
    {
        $this->parameters = ['frequence' => Frequencies::Once];

        $this->create()->update(1, UpdateType::All);

        $this->assertParents([null]);
    }

    /** @test */
    public function can_update_frequency_from_once_on_event()
    {
        $this->event->frequence = Frequencies::Once;

        $this->create();

        $this->parameters = [
            'frequence' => Frequencies::Daily,
            'recurrence_ends_at' => $this->date->addDays($this->interval - 1),
        ];

        $this->update(1, UpdateType::Futures);

        $this->assertParents([null, 1, 1, 1, 1]);
    }

    /** @test */
    public function can_update_single_event()
    {
        $this->parameters = ['end_time' => '20:20', 'frequence' => Frequencies::Once];

        $event = $this->create()->update(3, UpdateType::Single);

        $this->assertParents([null, 1, null, null, 4]);

        $this->assertDate($event->start_date, Event::first()->recurrence_ends_at);

        $this->assertEquals(Frequencies::Once, $event->frequence);
        $this->assertCount(1, Event::where($this->parameters)->get());
    }

    /** @test */
    public function can_update_non_frequent_event()
    {
        $this->event->frequence = Frequencies::Once;
        $this->parameters = ['end_time' => '20:20', 'frequence' => Frequencies::Once];

        $this->create()->update(1, UpdateType::Single);

        $this->assertCount(1, Event::where($this->parameters)->get());
    }

    /** @test */
    public function can_update_single_parent_event()
    {
        $this->parameters = ['end_time' => '20:20', 'frequence' => Frequencies::Once];

        $this->create()->update(1, UpdateType::Single);

        $this->assertParents([null, null, 2, 2, 2]);
        $this->assertCount(1, Event::where($this->parameters)->get());
    }
}
