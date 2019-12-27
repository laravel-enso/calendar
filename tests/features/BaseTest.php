<?php

namespace LaravelEnso\Calendar\tests\features;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Collection;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Core\app\Models\User;
use Tests\TestCase;

abstract class BaseTest extends TestCase
{
    use RefreshDatabase;

    protected Event $event;
    protected TestResponse $response;
    protected Carbon $date;
    protected int $count;
    protected array $parameters;

    protected function setUp() :void
    {
        parent::setUp();

        $this->seed();

        $this->actingAs(User::first());

        $this->event = factory(Event::class)->make();

        $this->date = Carbon::now()->startOfDay();
        $this->event->frequency = Frequencies::Daily;
        $this->count = 5;

        $this->parameters = [];
    }

    protected function assertStartDates($dates, $events = null)
    {
        $dates = (new Collection($dates))
            ->map(fn ($day) => now()->addDays($day)->toDateString())
            ->toArray();

        $events ??= Event::all();

        $this->assertEquals(
            $dates,
            $events->pluck('start_date')->map->toDateString()->toArray()
        );
    }

    protected function assertParents($parents, $events = null)
    {
        $events ??= Event::all();

        $this->assertEquals(
            $parents,
            $events->pluck('parent_id')->toArray()
        );
    }

    protected function assertDate($expected, $actual)
    {
        $expected = $expected instanceof Carbon
            ? $expected
            : Carbon::parse($expected);

        $actual = $actual instanceof Carbon
            ? $actual
            : Carbon::parse($actual);

        $this->assertEquals($expected->toDateString(), $actual->toDateString());
    }

    protected function create()
    {
        $recurrenceEndsAt = $this->date->clone()->addDays($this->count - 1);

        $this->response = $this->json('POST', route('core.calendar.events.store'), [
            'start_date' => $this->date->format('Y-m-d'),
        ] + [
            'end_date' => $this->date->format('Y-m-d'),
        ] + [
            'recurrence_ends_at' => $recurrenceEndsAt->format('Y-m-d'),
        ] + $this->event->toArray());

        return $this;
    }

    protected function update($eventId, int $updateType)
    {
        $parameters = $this->parameters +
            ['updateType' => $updateType] +
            Event::find($eventId)->toArray();

        $this->response = $this->json(
            'PATCH',
            route('core.calendar.events.update', ['event' => $eventId]),
            $parameters
        );

        return Event::find($eventId);
    }

    protected function deleteEvent(int $eventId, int $updateType): void
    {
        $this->response = $this->json(
            'DELETE',
            route('core.calendar.events.destroy', ['event' => $eventId]),
            ['updateType' => $updateType]
        );
    }
}
