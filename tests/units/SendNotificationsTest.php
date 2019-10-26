<?php

use Carbon\Carbon;
use Tests\TestCase;
use LaravelEnso\Core\app\Models\User;
use LaravelEnso\Calendar\app\Models\Reminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelEnso\Calendar\app\Notifications\ReminderNotification;

class SendNotificationsTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $faker;


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
        \Notification::fake();

        factory(Reminder::class)->create([
            'scheduled_at'=> Carbon::now()->addDay(),
            'created_by'=>$this->user->id
        ]);

        $this->artisan('enso:calendar:notify');

        \Notification::assertNothingSent();
    }

    /** @test */
    public function when_there_a_ready_reminders_then_should_notify_user()
    {
        \Notification::fake();

        $reminder = factory(Reminder::class)->create([
            'scheduled_at' => Carbon::now()->subDay(),
            'sent_at'=>null,
            'created_by' => $this->user->id
        ]);

        $this->artisan('enso:calendar:notify');

        \Notification::assertSentTo(
            config('auth.providers.users.model')::find($this->user->id),
            ReminderNotification::class
        );

        $this->assertNotNull($reminder->refresh()->sent_at);
    }
}
