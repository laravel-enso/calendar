<?php

namespace LaravelEnso\Calendars\tests\features;

use Carbon\Carbon;
use Tests\TestCase;
use LaravelEnso\Core\app\Models\User;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\Frequencies;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class BaseTest extends TestCase
{
    use RefreshDatabase;

    protected $event;
    protected $response;
    protected $date;
    protected $count;
    protected $parameters;

    protected function setUp() :void
    {
        parent::setUp();

        $this->seed();

        $this->actingAs(User::first());

        $this->event = factory(Event::class)->make();

        $this->date = now()->startOfDay();
        $this->event->frequence = Frequencies::Daily;
        $this->count = 5;

        $this->parameters = [];
    }


    protected function assertStartDates($dates, $events = null)
    {
        $dates = collect($dates)->map(function($day) {
            return now()->addDays($day)->toDateString();
        })->toArray();

        $events = $events ?? Event::all();

        $this->assertEquals(
            $dates,
            $events->pluck('start_date')->map->toDateString()->toArray()
        );
    }

    protected function assertParents($parents, $events = null)
    {
        $events = $events ?? Event::all();

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

    protected function dateFormat($date)
    {
        return $date->format(config('enso.config.dateFormat'));
    }

    protected function create()
    {
        $recurrenceEndsAt = $this->date->clone()->addDays($this->count - 1);

        $this->response = $this->json('POST', route('core.calendar.events.store'),
            ['start_date' => $this->dateFormat($this->date)] +
            ['end_date' => $this->dateFormat($this->date)] +
            ['recurrence_ends_at' => $this->dateFormat($recurrenceEndsAt)] +
            $this->event->toArray()
        );

        return $this;
    }

    protected function update($eventId, string $updateType)
    {
        $parameters = $this->parameters +
            ['update_type' => $updateType] +
            Event::find($eventId)->toArray();

        $parameters = collect($parameters)->map(function($value) {
            return $value instanceof Carbon
                ? $this->dateFormat($value)
                : $value;
        });

        $this->response = $this->json(
            'PATCH',
            route('core.calendar.events.update', ['event' => $eventId]),
            $parameters->toArray()
        );

        return Event::find($eventId);
    }

    protected function deleteEvent(int $eventId, string $updateType): void
    {
        $this->response = $this->json('DELETE',
            route('core.calendar.events.destroy', [
                'event' => $eventId,
                'updateType' => $updateType
            ])
        );
    }
}
