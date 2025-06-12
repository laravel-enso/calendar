<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use LaravelEnso\Calendar\Calendars\BirthdayCalendar;
use LaravelEnso\Calendar\Enums\Colors;
use LaravelEnso\Calendar\Models\Calendar;
use LaravelEnso\People\Models\Person;
use LaravelEnso\Users\Models\User;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    protected Calendar $calendar;
    protected Carbon $date;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed()
            ->actingAs(User::first());

        $this->calendar = Calendar::factory()->create();
    }

    /** @test */
    public function create_calendar()
    {
        $result = $this->post(route('core.calendar.store'), [
            'name' => 'test',
            'color' => Colors::Red->value,
            'private' => false,
        ]);

        $this->assertTrue(Calendar::whereName('test')->exists());
    }

    /** @test */
    public function can_limit_birthday_calendar_roles()
    {
        Person::first()->update(['birthday' => Carbon::today()]);
        Config::set('enso.calendar.birthdays.roles', []);

        $this->assertEmpty((new BirthdayCalendar())
            ->events(Person::first()->birthday, Person::first()->birthday->addDay()));
    }
}
