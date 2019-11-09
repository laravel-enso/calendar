<?php

namespace LaravelEnso\Calendar\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\Calendar\app\Notifications\ReminderNotification;

class Reminder extends Model
{
    use CreatedBy;

    protected $table = 'calendar_reminders';

    protected $fillable = ['event_id', 'scheduled_at', 'sent_at'];

    protected $dates = ['scheduled_at'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function send()
    {
        $this->createdBy->notify(new ReminderNotification($this));

        $this->update(['sent_at' => Carbon::now()]);
    }

    public function scopeReadyToNotify($query)
    {
        return $query->whereNull('sent_at')
            ->where('scheduled_at', '<=', Carbon::now());
    }
}
