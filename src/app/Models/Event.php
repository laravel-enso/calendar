<?php

namespace LaravelEnso\Calendar\app\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\Helpers\app\Traits\DateAttributes;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;
use LaravelEnso\Calendar\app\Contracts\Calendar as CalendarContract;

class Event extends Model implements ProvidesEvent
{
    use CreatedBy, DateAttributes;

    protected $table = 'calendar_events';

    protected $fillable = [
        'title', 'body', 'calendar', 'frequence', 'location', 'lat', 'lng',
        'starts_at', 'ends_at', 'recurrence_ends_at', 'is_all_day', 'is_readonly',
        'calendar_id', 'ends_time_at',
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
        return $this->attendees->pluck('id')->toArray();
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function setEndsTimeAtAttribute($value)
    {
        $this->ends_at = $this->ends_at
            ->setTimeFromTimeString($value);
    }

    public function setStartsAtAttribute($value)
    {
        $this->fillDateAttribute(
            'starts_at', $value, config('enso.config.dateFormat').' H:i'
        );
    }

    public function setEndsAtAttribute($value)
    {
        $this->fillDateAttribute(
            'ends_at', $value, config('enso.config.dateFormat').' H:i'
        );
    }

    public function setRecurrenceEndsAtAttribute($value)
    {
        $this->fillDateAttribute(
            'recurrence_ends_at', $value, config('enso.config.dateFormat')
        );
    }

    public function updateReminders($reminders)
    {
        $this->reminders()
            ->whereNotIn('id', $reminders->pluck('id'))
            ->delete();

        $reminders->each(function ($reminder) {
            Reminder::updateOrCreate(
                ['id' => $reminder['id']], $reminder
            );
        });

        return $this;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function body(): ?string
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

    public function location(): ?string
    {
        return $this->location;
    }

    public function getCalendar(): CalendarContract
    {
        return Calendar::cacheGet($this->calendar_id);
    }

    public function frequence(): int
    {
        return $this->frequence;
    }

    public function recurrenceEnds(): ?Carbon
    {
        return $this->recurrence_ends_at;
    }

    public function allDay(): bool
    {
        return $this->is_all_day;
    }

    public function readonly(): bool
    {
        return $this->is_readonly;
    }

    public function scopeAllowed($query)
    {
        $query->when(
            ! Auth::user()->isAdmin() && ! Auth::user()->isSupervisor(),
            function ($query) {
                $query->whereHas('createdBy.person.companies', function ($companies) {
                    $companies->whereIn(
                        'id', Auth::user()->person->companies()->pluck('id')
                    );
                });
        });
    }

    public function scopeFor($query, $calendars)
    {
        $query->whereIn('calendar_id', $calendars->pluck('id'));
    }
}
