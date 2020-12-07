<?php

namespace LaravelEnso\Calendar\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Calendar\Notifications\ReminderNotification;
use LaravelEnso\TrackWho\Traits\CreatedBy;

class Reminder extends Model
{
    use CreatedBy, HasFactory;

    protected $table = 'calendar_reminders';

    protected $guarded = ['id'];

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

    public function scopeNotSent($query)
    {
        return $query->whereNull('sent_at');
    }

    public function scopeOverdue($query)
    {
        return $query->where('scheduled_at', '<=', Carbon::now());
    }

    public function scopeShouldSend($query)
    {
        return $query->notSent()->overdue();
    }
}
