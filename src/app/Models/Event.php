<?php

namespace LaravelEnso\Calendar\app\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Calendar\app\Services\Request;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\Calendar\app\Services\Frequency;
use LaravelEnso\Helpers\app\Traits\DateAttributes;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

class Event extends Model implements ProvidesEvent
{
    use CreatedBy, DateAttributes;

    protected $table = 'calendar_events';

    protected $fillable = [
        'title', 'body', 'calendar', 'frequence', 'location', 'lat', 'lng',
        'starts_at', 'ends_at', 'recurrence_ends_at', 'is_all_day', 'is_readonly',
        'calendar_id', 'starts_time_at', 'ends_time_at',
    ];

    protected $casts = ['is_all_day' => 'boolean', 'is_readonly' => 'boolean'];

    protected $dates = ['starts_at', 'ends_at', 'recurrence_ends_at'];

    public function attendees()
    {
        return $this->belongsToMany(User::class);
    }

    public function calendar()
    {
        return $this->belongsTo(Calendar::class, 'calendar_id', 'id');
    }

    public function attendeeList()
    {
        return $this->attendees->pluck('id');
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function setStartsTimeAtAttribute($value)
    {
        $this->starts_at = $this->starts_at
            ->setTimeFromTimeString($value);
    }

    public function setEndsTimeAtAttribute($value)
    {
        $this->ends_at = $this->ends_at
            ->setTimeFromTimeString($value);
    }

    public function setStartsAtAttribute($value)
    {
        $this->fillDateAttribute('starts_at', $value,
            config('enso.config.dateFormat').' H:i');
    }

    public function setEndsAtAttribute($value)
    {
        $this->fillDateAttribute('ends_at', $value,
            config('enso.config.dateFormat').' H:i');
    }

    public function setRecurrenceEndsAtAttribute($value)
    {
        $this->fillDateAttribute('recurrence_ends_at', $value,
            config('enso.config.dateFormat'));
    }

    public function updateReminders($reminders)
    {
        $this->reminders()
            ->whereNotIn('id', $reminders->pluck('id'))
            ->delete();

        $reminders->each(function ($reminder) {
            return isset($reminder['id'])
                ? Reminder::find($reminder['id'])->update($reminder)
                : Reminder::create($reminder);
        });
    }

    public function title()
    {
        return $this->title;
    }

    public function body()
    {
        return $this->body;
    }

    public function start(): Carbon
    {
        return $this->starts_at;
    }

    public function end(): Carbon
    {
        return $this->ends_at;
    }

    public function location()
    {
        return $this->location;
    }

    public function getCalendar()
    {
        return Calendar::cacheGet($this->calendar_id);
    }

    public function frequence()
    {
        return $this->frequence;
    }

    public function recurrenceEnds(): ?Carbon
    {
        return $this->recurrence_ends_at;
    }

    public function allDay()
    {
        return $this->is_all_day;
    }

    public function readonly()
    {
        return $this->is_readonly;
    }

    public function scopeAllowed($query)
    {
        $query->when(! Auth::user()->belongsToAdminGroup(), function ($query) {
            $query->whereHas('createdBy.person.companies', function ($companies) {
                $companies->whereIn(
                    'id', Auth::user()->person->companies()->pluck('id')
                );
            });
        });
    }

    public function scopeCalendars($query, $calendars)
    {
        $query->whereHas('calendar', function ($calendar) use ($calendars) {
            $calendar->whereIn('id', $calendars);
        });
    }

    public function scopeBetween($query, Request $request)
    {
        (new Frequency($request))->query($query);
    }
}
