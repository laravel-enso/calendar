<?php

namespace LaravelEnso\Calendar\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use LaravelEnso\Calendar\Models\Reminder;

class ReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Reminder $reminder)
    {
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
        $app = Config::get('app.name');

        return (new MailMessage())
            ->subject("[ {$app} ] {$this->subject()}")
            ->markdown('laravel-enso/calendar::emails.reminder', [
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

    private function subject(): string
    {
        return __(
            'Notification, :title',
            ['title' => $this->reminder->event->title]
        );
    }
}
