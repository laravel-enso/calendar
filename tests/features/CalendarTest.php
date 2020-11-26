<?php

namespace LaravelEnso\Calendar\tests\features;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use LaravelEnso\Calendar\Calendars\BirthdayCalendar;
use LaravelEnso\Calendar\Enums\Colors;
use LaravelEnso\Calendar\Models\Calendar;
use LaravelEnso\Core\Models\User;
use Tests\TestCase;
use LaravelEnso\Calendar\Facades\Calendars;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    protected Calendar $calendar;
    protected Carbon $date;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->actingAs(User::first());

        $this->calendar = Calendar::factory()->create();
    }

    /** @test */
    public function create_calendar()
    {
        $result = $this->post(route('core.calendar.store'), [
            'name' => 'test',
            'color' => Colors::Red,
            'private' => false,
        ]);

        $this->assertTrue(Calendar::whereName('test')->exists());
    }

    /** @test */
    public function cannot_get_calendar_without_role_access()
    {
        Config::set('enso.calendar.roles.'.BirthdayCalendar::class, []);

        Calendars::register(BirthdayCalendar::class);

        $result = $this->get(route('core.calendar.index'));

        $this->assertEmpty(Calendars::all()
            ->filter(fn ($calendar) => BirthdayCalendar::class === get_class($calendar)));
    }
}
