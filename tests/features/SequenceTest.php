<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\Calendar\Enums\Frequencies;
use LaravelEnso\Calendar\Enums\UpdateType;
use LaravelEnso\Calendar\Models\Event;
use LaravelEnso\Core\Models\User;
use Tests\TestCase;

class SequenceTest extends TestCase
{
    use RefreshDatabase;

    protected Event $event;
    protected Carbon $date;
    protected int $count;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->actingAs(User::first());

        $this->date = Carbon::today();
        $this->count = 5;

        $this->event = Event::factory()->make([
            'start_date' => $this->date->format('Y-m-d'),
            'end_date' => $this->date->format('Y-m-d'),
            'recurrence_ends_at' => $this->date->clone()->addDays($this->count - 1),
            'frequency' => Frequencies::Daily,
        ]);

        $this->event->store();
    }

    /** @test */
    public function can_update_all_events_from_middle_of_sequence()
    {
        $endTime = '22:20';

        $this->patch($this->route('update', 3), [
            'end_time' => $endTime,
            'updateType' => UpdateType::All,
        ]);

        $startTime = $this->date->format('Y-m-d').' '.$this->event->start_time;

        $this->assertCount($this->count, Event::whereEndTime($endTime)->get());
    }

    /** @test */
    public function can_update_this_and_future_events()
    {
        $startingId = 3;
        $endTime = '22:20';

        $this->patch($this->route('update', $startingId), [
            'end_time' => $endTime,
            'updateType' => UpdateType::ThisAndFuture,
        ]);

        $events = Event::orderBy('id')->get();

        $this->assertEquals($events->pluck('parent_id')->toArray(), [null, 1, null, 3, 3]);

        $events->filter(fn ($event) => $event->id >= $startingId)
            ->each(fn ($event) => $this->assertEquals($endTime, $event->end_time));

        $this->assertRecurrenceEndsAt($startingId);
    }

    /** @test */
    public function can_update_date_of_event_in_middle_of_sequence()
    {
        $startingId = 3;
        $date = $this->date->clone()->addDays(1);

        $this->patch($this->route('update', $startingId), [
            'start_date' => $date->format('Y-m-d'),
            'end_date' => $date->format('Y-m-d'),
            'updateType' => UpdateType::OnlyThis,
        ]);

        $events = Event::orderBy('id')->get();

        $this->assertEquals($events->pluck('parent_id')->toArray(), [null, 1, null, null, 4]);
    }

    /** @test */
    public function can_regenerate_future_events()
    {
        $startingId = 3;
        $date = Carbon::today()->addDays(3)->format('Y-m-d');

        $this->patch($this->route('update', $startingId), [
            'start_date' => $date,
            'end_date' => $date,
            'updateType' => UpdateType::ThisAndFuture,
            'frequency' => $this->event->frequency,
            'recurrence_ends_at' => $this->event->recurrence_ends_at,
        ]);

        $parents = Event::orderBy('id')->pluck('parent_id')->toArray();

        $this->assertEquals([null, 1, null, 3], $parents);
    }

    /** @test */
    public function cannot_predate_subsequence()
    {
        $startingId = 3;
        $date = Carbon::yesterday()->format('Y-m-d');

        $this->patch($this->route('update', $startingId), [
            'start_date' => $date,
            'end_date' => $date,
            'updateType' => UpdateType::ThisAndFuture,
        ])->assertStatus(302)
            ->assertSessionHasErrors(['start_date']);
    }

    /** @test */
    public function can_update_recurrence_ends_at()
    {
        $startingId = 1;
        $date = Carbon::today()->addDays(7)->format('Y-m-d');

        $this->patch($this->route('update', $startingId), [
            'recurrence_ends_at' => $date,
            'updateType' => UpdateType::ThisAndFuture,
        ]);

        $parents = Event::orderBy('id')->pluck('parent_id')->toArray();

        $this->assertEquals([null, 1, 1, 1, 1, 1, 1, 1], $parents);
    }

    /** @test */
    public function can_update_frequency_to_once_on_only_one_event_from_sequence()
    {
        $startingId = 3;

        $this->patch($this->route('update', $startingId), [
            'frequency' => Frequencies::Once,
            'updateType' => UpdateType::OnlyThis,
        ]);

        $parents = Event::orderBy('id')->pluck('parent_id')->toArray();

        $this->assertEquals([null, 1, null, null, 4], $parents);
    }

    /** @test */
    public function cannot_update_frequency_to_once_on_many_events_from_sequence()
    {
        $startingId = 3;

        $this->patch($this->route('update', $startingId), [
            'frequency' => Frequencies::Once,
            'updateType' => UpdateType::ThisAndFuture,
        ])->assertStatus(302)
            ->assertSessionHasErrors(['frequency']);
    }

    /** @test */
    public function can_delete_all_events_from_middle_of_sequence()
    {
        $this->delete($this->route('destroy', 3), ['updateType' => UpdateType::All]);

        $this->assertTrue(Event::doesntExist());
    }

    /** @test */
    public function can_delete_single_event_from_sequence()
    {
        $id = 3;

        $this->delete($this->route('destroy', $id), ['updateType' => UpdateType::OnlyThis]);

        $events = Event::orderBy('id')->where('id', '>=', $id)->get();

        $this->assertFalse($events->pluck('id')->contains($id));
        $this->assertEquals([null, $id + 1], $events->pluck('parent_id')->toArray());
    }

    /** @test */
    public function can_delete_following_events()
    {
        $id = 3;

        $this->delete($this->route('destroy', $id), ['updateType' => UpdateType::ThisAndFuture]);

        $this->assertTrue(Event::orderBy('id')->where('id', '>=', $id)->doesntExist());

        $this->assertRecurrenceEndsAt($id);
    }

    private function route(string $action, int $eventId)
    {
        return route("core.calendar.events.{$action}", ['event' => $eventId]);
    }

    private function assertRecurrenceEndsAt(int $breakPoint): void
    {
        Event::orderBy('id')->get()->filter(fn($event) => $event->id < $breakPoint)
            ->each(fn($event) => $this->assertEquals(
                $this->date->clone()->addDays($breakPoint - 2)->startOfDay(),
                $event->recurrence_ends_at->startOfDay()
            ));
    }
}
