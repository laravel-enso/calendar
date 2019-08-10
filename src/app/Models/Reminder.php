<?php

namespace LaravelEnso\Calendar\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\Helpers\app\Traits\DateAttributes;
use LaravelEnso\Calendar\app\Notifications\ReminderNotification;

class Reminder extends Model
{
    use CreatedBy, DateAttributes;

    protected $fillable = ['event_id', 'remind_at', 'reminded_at'];

    protected $dates = ['remind_at'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function notify()
    {
        $this->createdBy->notify(new ReminderNotification($this));

        $this->update(['reminded_at' => Carbon::now()]);
    }

    public function scopeReadyForNotify($query)
    {
        return $query->whereNull('reminded_at')
            ->where('remind_at', '<=', Carbon::now());
    }

    public function setRemindAtAttribute($value)
    {
        $this->fillDateAttribute('remind_at', $value, 'Y-m-d H:i:s');
    }
}
