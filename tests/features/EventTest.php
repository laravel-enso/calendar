<?php

namespace LaravelEnso\Calendar\tests\features;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use LaravelEnso\Calendar\Enums\Frequencies;
use LaravelEnso\Calendar\Models\Event;
use LaravelEnso\Core\Models\User;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    protected Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->actingAs(User::first());

        $this->event = Event::factory()->make([
            'frequency' => Frequencies::Once,
        ]);

        $this->event->store();
    }

    /** @test */
    public function can_update_event()
    {
        $endTime = '22:20';

        $this->patch($this->route('update'), ['end_time' => $endTime]);

        $this->assertEquals($endTime, $this->event->refresh()->end_time);
    }

    /** @test */
    public function can_update_to_sequence()
    {
        $this->patch($this->route('update'), [
            'frequency' => Frequencies::Daily,
            'recurrence_ends_at' => $this->event->start_date->clone()->addDays(4)->format('Y-m-d'),
        ]);

        $assertion = (new Collection([null]))->pad(5, 1)->toArray();
        $events = Event::orderBy('id');

        $this->assertEquals($assertion, $events->pluck('parent_id')->toArray());
    }

    /** @test */
    public function can_delete_event()
    {
        $this->delete($this->route('destroy'));

        $this->assertTrue(Event::doesntExist());
    }

    private function route(string $action)
    {
        return route("core.calendar.events.{$action}", ['event' => $this->event->id]);
    }
}
