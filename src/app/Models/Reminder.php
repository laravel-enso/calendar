<?php

namespace LaravelEnso\Calendar\app\Models;

use Carbon\Carbon;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;

class Reminder extends Model
{
    use CreatedBy;

    protected $fillable = ['event_id', 'remind_at'];

    protected $dates = ['remind_at'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function setRemindAtAttribute($value)
    {
        $this->attributes['remind_at'] =
            Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
