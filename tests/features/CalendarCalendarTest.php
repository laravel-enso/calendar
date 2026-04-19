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
use PHPUnit\Framework\Attributes\Test;

class CalendarCalendarTest extends TestCase
{
    use RefreshDatabase;

    protected Calendar $calendar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed()
            ->actingAs(User::first());

        $this->calendar = Calendar::factory()->create();
    }

    #[Test]
    public function create_calendar()
    {
        $this->post(route('core.calendar.store'), [
            'name' => 'test',
            'color' => Colors::Red,
            'private' => false,
        ]);

        $this->assertTrue(Calendar::whereName('test')->exists());
    }

    #[Test]
    public function can_list_calendars()
    {
        $response = $this->getJson(route('core.calendar.index'));

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $this->calendar->id,
                'name' => $this->calendar->name,
                'readonly' => false,
            ])
            ->assertJsonFragment([
                'id' => 'birthday-calendar',
                'name' => 'Birthdays',
                'readonly' => true,
            ]);
    }

    #[Test]
    public function can_get_calendar_forms_and_options()
    {
        $this->getJson(route('core.calendar.create'))
            ->assertOk()
            ->assertJsonStructure(['form']);

        $this->getJson(route('core.calendar.edit', $this->calendar))
            ->assertOk()
            ->assertJsonStructure(['form']);

        $this->getJson(route('core.calendar.options'))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $this->calendar->id,
                'name' => $this->calendar->name,
            ]);
    }

    #[Test]
    public function can_update_and_delete_calendar()
    {
        $this->patchJson(route('core.calendar.update', $this->calendar), [
            'name' => 'updated-calendar',
            'color' => Colors::Blue,
            'private' => true,
        ])->assertOk()
            ->assertJsonFragment([
                'message' => __('The calendar was updated!'),
            ]);

        $this->calendar->refresh();

        $this->assertSame('updated-calendar', $this->calendar->name);
        $this->assertSame(Colors::Blue, $this->calendar->color);
        $this->assertTrue($this->calendar->private);

        $this->deleteJson(route('core.calendar.destroy', $this->calendar))
            ->assertOk()
            ->assertJsonFragment([
                'message' => __('The calendar was successfully deleted'),
            ]);

        $this->assertDatabaseMissing('calendars', [
            'id' => $this->calendar->id,
        ]);
    }

    #[Test]
    public function can_limit_birthday_calendar_roles()
    {
        Person::first()->update(['birthday' => Carbon::today()]);
        Config::set('enso.calendar.birthdays.roles', []);

        $this->assertEmpty((new BirthdayCalendar())
            ->events(Person::first()->birthday, Person::first()->birthday->addDay()));
    }
}
