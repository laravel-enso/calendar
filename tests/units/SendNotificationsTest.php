<?php

namespace LaravelEnso\Calendars\tests\units;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use LaravelEnso\Calendar\app\Models\Reminder;
use LaravelEnso\Calendar\app\Notifications\ReminderNotification;
use LaravelEnso\Core\app\Models\User;
use Tests\TestCase;

class SendNotificationsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        // $this->withoutExceptionHandling();

        $this->seed()
            ->actingAs($this->user = User::first());
    }

    /** @test */
    public function when_there_is_no_ready_reminders_then_should_not_notify_user()
    {
        Notification::fake();

        factory(Reminder::class)->create([
            'scheduled_at' => Carbon::now()->addDay(),
            'created_by' => $this->user->id
        ]);

        $this->artisan('enso:calendar:send-reminders');

        Notification::assertNothingSent();
    }

    /** @test */
    public function when_there_a_ready_reminders_then_should_notify_user()
    {
        Notification::fake();

        $reminder = factory(Reminder::class)->create([
            'scheduled_at' => Carbon::now()->subDay(),
            'sent_at' => null,
            'created_by' => $this->user->id
        ]);

        $this->artisan('enso:calendar:send-reminders');

        Notification::assertSentTo(
            config('auth.providers.users.model')::find($this->user->id),
            ReminderNotification::class
        );

        $this->assertNotNull($reminder->refresh()->sent_at);
    }
}
