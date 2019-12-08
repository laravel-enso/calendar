<?php

namespace LaravelEnso\Calendar\app\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LaravelEnso\Calendar\app\Models\Reminder;

class ReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function via()
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toBroadcast()
    {
        return new BroadcastMessage([
            'level' => 'info',
            'title' => __('Reminder'),
            'icon' => 'bell',
            'body' => __('Reminder: :title', ['title' => $this->reminder->event->title]),
        ]);
    }

    public function toMail()
    {
        return (new MailMessage())
            ->subject(__(
                config('app.name')).': '.__('Notification, :title',
                ['title' => $this->reminder->event->title]
            ))->markdown('laravel-enso/calendar::emails.reminder_notify', [
                'appellative' => $this->reminder->createdBy->person->appellative,
                'url' => url('/calendar'),
                'title' => $this->reminder->event->title,
                'starts_at' => $this->reminder->event->starts_at,
                'ends_at' => $this->reminder->event->ends_at,
            ]);
    }

    public function toArray()
    {
        return [
            'body' => $this->reminder->event->title,
            'path' => '/calendar',
            'icon' => 'bell',
        ];
    }
}
