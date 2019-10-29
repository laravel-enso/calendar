<?php

namespace LaravelEnso\Calendar\app\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\Helpers\app\Traits\DateAttributes;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;
use LaravelEnso\Calendar\app\Services\Frequency\Create;
use LaravelEnso\Calendar\app\Services\Frequency\Delete;
use LaravelEnso\Calendar\app\Services\Frequency\Update;
use LaravelEnso\Calendar\app\Contracts\Calendar as CalendarContract;

class Event extends Model implements ProvidesEvent
{
    use CreatedBy, DateAttributes;

    protected $table = 'calendar_events';

    protected $fillable = [
        'title', 'body', 'calendar', 'frequence', 'location', 'lat', 'lng',
        'starts_on', 'ends_on', 'starts_time', 'ends_time', 'is_all_day',
        'recurrence_ends_at', 'is_readonly', 'calendar_id', 'parent_id',
    ];

    protected $casts = ['is_all_day' => 'boolean', 'is_readonly' => 'boolean'];

    protected $dates = ['starts_on', 'ends_on', 'recurrence_ends_at'];

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function events()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

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

    public function setStartsOnAttribute($value)
    {
        $this->fillDateAttribute(
            'starts_on', $value, config('enso.config.dateFormat')
        );
    }

    public function setEndsOnAttribute($value)
    {
        $this->fillDateAttribute(
            'ends_on', $value, config('enso.config.dateFormat')
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
        return $this->starts_on
            ->setTimeFromTimeString($this->starts_time);
    }

    public function end(): Carbon
    {
        return $this->ends_on
            ->setTimeFromTimeString($this->ends_time);
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

    public function createEvent($attributes)
    {
        tap($this)->fill($attributes)->save();

        (new Create($this))->handle();

        return $this;
    }

    public function updateEvent($attributes, $updateType)
    {
        $this->update($attributes);

        (new Update($this))->handle($updateType);

        return $this;
    }

    public function deleteEvent($updateType)
    {
        $this->delete();

        (new Delete($this))->handle($updateType);

        return $this;
    }

    public function createReminders($reminders)
    {
        $this->reminders()->createMany($reminders);

        return $this;
    }

    public function syncAttendees($attendees)
    {
        $this->attendees()->sync($attendees);

        return $this;
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

    public function scopeBetween($query, Carbon $start, Carbon $end)
    {
        $query->where('ends_on', '<=', $end)
            ->where('starts_on', '>=', $start);
    }
}
