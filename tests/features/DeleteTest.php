<?php

namespace LaravelEnso\Calendars\tests\features;

use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\UpdateType;
use LaravelEnso\Calendar\app\Enums\Frequencies;

class DeleteTest extends BaseTest
{
    /** @test */
    public function can_delete_all_events()
    {
        $this->create()->deleteEvent(3, UpdateType::All);

        $this->assertEmpty(Event::all());
    }

    /** @test */
    public function can_delete_single_event()
    {
        $this->create()->deleteEvent(3, UpdateType::Single);

        $this->assertParents([null, 1, null, 4]);
    }


    /** @test */
    public function can_delete_non_frequent_event()
    {
        $this->event->frequence = Frequencies::Once;

        $this->create()->deleteEvent(1, UpdateType::Single);

        $this->assertEmpty(Event::all());
    }

    /** @test */
    public function can_delete_parent_event()
    {
        $this->create()->deleteEvent(1, UpdateType::Single);

        $this->assertParents([null, 2, 2, 2]);
    }

    /** @test */
    public function can_delete_following_events()
    {
        $this->count = 5;

        $this->create()->deleteEvent(3, UpdateType::Futures);

        $this->assertParents([null, 1]);
    }

}
