<?php

namespace LaravelEnso\Calendar\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use LaravelEnso\Calendar\app\Contracts\Calendar as CalendarContract;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;
use LaravelEnso\Calendar\app\Services\Frequency\Create;
use LaravelEnso\Calendar\app\Services\Frequency\Delete;
use LaravelEnso\Calendar\app\Services\Frequency\Update;
use LaravelEnso\Core\app\Models\User;
use LaravelEnso\Rememberable\app\Traits\Rememberable;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;

class Event extends Model implements ProvidesEvent
{
    use CreatedBy, Rememberable;

    protected $table = 'calendar_events';

    protected $fillable = [
        'title', 'body', 'calendar', 'frequency', 'location', 'lat', 'lng',
        'start_date', 'end_date', 'start_time', 'end_time', 'is_all_day',
        'recurrence_ends_at', 'calendar_id', 'parent_id',
    ];

    protected $casts = ['is_all_day' => 'boolean'];

    protected $dates = ['start_date', 'end_date', 'recurrence_ends_at'];

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
        return $this->belongsToMany(User::class, 'calendar_event_user');
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
        return $this->start_date
            ->setTimeFromTimeString($this->start_time);
    }

    public function end(): Carbon
    {
        return $this->end_date
            ->setTimeFromTimeString($this->end_time);
    }

    public function location(): ?string
    {
        return $this->location;
    }

    public function getCalendar(): CalendarContract
    {
        return Calendar::cacheGet($this->calendar_id);
    }

    public function frequency(): int
    {
        return $this->frequency;
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
        return false;
    }

    public function createEvent($attributes)
    {
        $this->fill($attributes)->save();

        (new Create($this))->handle();

        return $this;
    }

    public function updateEvent($attributes, $updateType)
    {
        (new Update($this))->handle($attributes, $updateType);

        return $this;
    }

    public function deleteEvent($updateType)
    {
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

    public function scopeSequence($query, $parentId)
    {
        $query->where(function ($query) use ($parentId) {
            $query->whereParentId($parentId)
                ->orWhere('id', $parentId);
        });
    }

    public function scopeBetween($query, Carbon $start, Carbon $end)
    {
        $query->where('end_date', '<=', $end)
            ->where('start_date', '>=', $start);
    }
}
