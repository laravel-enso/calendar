<?php

require_once __DIR__.'/../Fixtures/CustomCalendarFixtures.php';

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\Calendar\Facades\Calendars;
use LaravelEnso\Calendar\Models\Calendar;
use LaravelEnso\Calendar\Models\Event;
use LaravelEnso\Users\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CalendarEventsIndexTest extends TestCase
{
    use RefreshDatabase;

    private Carbon $startDate;
    private Carbon $endDate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->actingAs(User::first());

        $this->startDate = Carbon::parse('2026-04-10');
        $this->endDate = Carbon::parse('2026-04-12');
    }

    #[Test]
    public function filters_events_by_selected_calendars(): void
    {
        $includedCalendar = Calendar::factory()->create();
        $excludedCalendar = Calendar::factory()->create();

        $includedEvent = Event::factory()->create([
            'calendar_id' => $includedCalendar->id,
            'title' => 'included-event',
            'start_date' => $this->startDate->toDateString(),
            'end_date' => $this->startDate->toDateString(),
        ]);

        Event::factory()->create([
            'calendar_id' => $excludedCalendar->id,
            'title' => 'excluded-event',
            'start_date' => $this->startDate->toDateString(),
            'end_date' => $this->startDate->toDateString(),
        ]);

        Event::factory()->create([
            'calendar_id' => $includedCalendar->id,
            'title' => 'outside-window-event',
            'start_date' => $this->endDate->copy()->addDay()->toDateString(),
            'end_date' => $this->endDate->copy()->addDay()->toDateString(),
        ]);

        $this->getJson(route('core.calendar.events.index', [
            'startDate' => $this->startDate->toDateString(),
            'endDate' => $this->endDate->toDateString(),
            'calendars' => [$includedCalendar->id],
        ]))->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'id' => $includedEvent->id,
                'title' => 'included-event',
                'readonly' => false,
            ])
            ->assertJsonMissing([
                'title' => 'excluded-event',
            ])
            ->assertJsonMissing([
                'title' => 'outside-window-event',
            ]);
    }

    #[Test]
    public function merges_native_and_custom_calendar_events_in_index_response(): void
    {
        $calendar = Calendar::factory()->create();

        Event::factory()->create([
            'calendar_id' => $calendar->id,
            'title' => 'native-event',
            'start_date' => $this->startDate->toDateString(),
            'end_date' => $this->startDate->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);

        Calendars::register([new CalendarTestCustomCalendar()]);

        $this->getJson(route('core.calendar.events.index', [
            'startDate' => $this->startDate->toDateString(),
            'endDate' => $this->endDate->toDateString(),
            'calendars' => [$calendar->id, 'calendar-test-custom'],
        ]))->assertOk()
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'title' => 'native-event',
                'readonly' => false,
            ])
            ->assertJsonFragment([
                'title' => 'custom-event',
                'readonly' => true,
                'location' => 'remote',
            ]);
    }
}
