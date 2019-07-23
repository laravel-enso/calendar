<?php

namespace LaravelEnso\Calendar\app\Notifications;

use URL;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use LaravelEnso\Calendar\app\Models\Reminder;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'level' => 'info',
            'title' => __('Reminder'),
            'icon' => 'bell',
            'body' => __('Reminder: :title',['title'=>$this->reminder->event->title]),
        ]);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(__(config('app.name')).': '.__('Notification, :title',['title'=>$this->reminder->event->title]))
            ->markdown('laravel-enso/calendar::emails.reminder_notify', [
                'appellative' => $this->reminder->createdBy->person->appellative,
                'url' => url('/calendar'),
                'title' => $this->reminder->event->title,
                'starts_at' => $this->reminder->event->starts_at,
                'ends_at' => $this->reminder->event->ends_at,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'body' => $this->reminder->event->title,
            'path' => '/calendar',
            'icon' => 'bell',
        ];
    }
}
