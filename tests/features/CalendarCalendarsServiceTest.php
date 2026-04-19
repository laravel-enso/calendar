<?php

require_once __DIR__.'/../Fixtures/CustomCalendarFixtures.php';

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\Calendar\Facades\Calendars;
use LaravelEnso\Calendar\Models\Calendar;
use LaravelEnso\Roles\Models\Role;
use LaravelEnso\Users\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CalendarCalendarsServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function registers_deduplicates_and_removes_custom_calendars(): void
    {
        $this->seed();
        $this->actingAs(User::first());

        $service = app('calendars');
        $calendar = new CalendarTestCustomCalendar();

        $service->register([$calendar, $calendar]);

        $this->assertSame(1, $service->all()->filter(
            fn ($existing) => $existing->getKey() === $calendar->getKey()
        )->count());
        $this->assertTrue($service->keys()->contains($calendar->getKey()));

        $service->remove($calendar->getKey());

        $this->assertFalse($service->keys()->contains($calendar->getKey()));
    }

    #[Test]
    public function filters_available_calendars_by_permissions(): void
    {
        $this->seed();

        $roleId = Role::query()->whereNotIn('id', [1, 2])->value('id');
        $owner = User::factory()->create(['role_id' => $roleId]);
        $viewer = User::factory()->create(['role_id' => $roleId]);

        $this->actingAs($owner);
        $publicCalendar = Calendar::factory()->create([
            'name' => 'public-calendar',
            'private' => false,
        ]);
        $privateCalendar = Calendar::factory()->create([
            'name' => 'private-calendar',
            'private' => true,
        ]);

        $this->actingAs($viewer);

        Calendars::register([
            new CalendarTestCustomCalendar(),
            new CalendarTestPrivateCustomCalendar(),
        ]);

        $service = app('calendars');
        $available = $service->all();

        $this->assertTrue($available->contains(fn ($calendar) => $calendar->getKey() === $publicCalendar->id));
        $this->assertFalse($available->contains(fn ($calendar) => $calendar->getKey() === $privateCalendar->id));
        $this->assertTrue($available->contains(fn ($calendar) => $calendar->getKey() === 'calendar-test-custom'));
        $this->assertFalse($available->contains(fn ($calendar) => $calendar->getKey() === 'calendar-test-private-custom'));
        $this->assertTrue($available->contains(fn ($calendar) => $calendar->getKey() === 'birthday-calendar'));
    }
}
