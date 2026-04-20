<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use LaravelEnso\Calendar\Models\Reminder;
use LaravelEnso\Calendar\Notifications\ReminderNotification;
use LaravelEnso\Users\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CalendarSendNotificationsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed()
            ->actingAs($this->user = User::first());
    }

    #[Test]
    public function when_there_is_no_ready_reminders_then_should_not_notify_user()
    {
        Notification::fake();

        Reminder::factory()->create([
            'scheduled_at' => Carbon::now()->addDay(),
            'created_by'   => $this->user->id,
        ]);

        $this->artisan('enso:calendar:send-reminders');

        Notification::assertNothingSent();
    }

    #[Test]
    public function when_there_a_ready_reminders_then_should_notify_user()
    {
        Notification::fake();

        $reminder = Reminder::factory()->create([
            'scheduled_at' => Carbon::now()->subDay(),
            'sent_at'      => null,
            'created_by'   => $this->user->id,
        ]);

        $this->artisan('enso:calendar:send-reminders');

        Notification::assertSentTo(
            config('auth.providers.users.model')::find($this->user->id),
            ReminderNotification::class
        );

        $this->assertNotNull($reminder->refresh()->sent_at);
    }

    #[Test]
    public function reminder_scopes_return_expected_records()
    {
        $sentReminder = Reminder::factory()->create([
            'scheduled_at' => Carbon::now()->subDay(),
            'sent_at'      => Carbon::now(),
            'created_by'   => $this->user->id,
        ]);

        $overdueReminder = Reminder::factory()->create([
            'scheduled_at' => Carbon::now()->subHour(),
            'sent_at'      => null,
            'created_by'   => $this->user->id,
        ]);

        $futureReminder = Reminder::factory()->create([
            'scheduled_at' => Carbon::now()->addHour(),
            'sent_at'      => null,
            'created_by'   => $this->user->id,
        ]);

        $this->assertEqualsCanonicalizing(
            [$overdueReminder->id, $futureReminder->id],
            Reminder::notSent()->pluck('id')->all()
        );

        $this->assertEquals(
            [$overdueReminder->id],
            Reminder::overdue()->notSent()->pluck('id')->all()
        );

        $this->assertEquals(
            [$overdueReminder->id],
            Reminder::shouldSend()->pluck('id')->all()
        );

        $this->assertTrue(Reminder::whereKey($sentReminder->id)->exists());
    }

    #[Test]
    public function reminder_send_marks_record_as_sent()
    {
        Notification::fake();

        $reminder = Reminder::factory()->create([
            'scheduled_at' => Carbon::now()->subMinute(),
            'sent_at'      => null,
            'created_by'   => $this->user->id,
        ]);

        $reminder->send();

        Notification::assertSentTo(
            config('auth.providers.users.model')::find($this->user->id),
            ReminderNotification::class
        );
        $this->assertNotNull($reminder->refresh()->sent_at);
    }
}
