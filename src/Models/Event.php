<?php

namespace LaravelEnso\Calendar\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LaravelEnso\Calendar\Contracts\Calendar as CalendarContract;
use LaravelEnso\Calendar\Contracts\ProvidesEvent;
use LaravelEnso\Calendar\Enums\Frequencies;
use LaravelEnso\Calendar\Services\Frequency\Create;
use LaravelEnso\Calendar\Services\Frequency\Delete;
use LaravelEnso\Calendar\Services\Frequency\Update;
use LaravelEnso\Core\Models\User;
use LaravelEnso\Helpers\Traits\HasFactory;
use LaravelEnso\Rememberable\Traits\Rememberable;
use LaravelEnso\TrackWho\Traits\CreatedBy;

class Event extends Model implements ProvidesEvent
{
    use CreatedBy, HasFactory, Rememberable;

    protected $table = 'calendar_events';

    protected $guarded = ['id'];

    protected $casts = [
        'is_all_day' => 'boolean', 'parent_id' => 'integer', 'calendar_id' => 'integer',
        'frequency' => 'integer', 'created_by' => 'integer',
    ];

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

        $reminders->each(fn ($reminder) => Reminder::updateOrCreate(
            ['id' => $reminder['id']],
            $reminder
        ));

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
        return $this->start_date->setTimeFromTimeString($this->start_time);
    }

    public function end(): Carbon
    {
        return $this->end_date->setTimeFromTimeString($this->end_time);
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

    public function store(?int $updateType = null)
    {
        if ($this->exists && $updateType !== null) {
            $this->createOrUpdateSequence($updateType);
        } else {
            $this->saveOneOrCreateSequence();
        }
    }

    public function remove(?int $updateType)
    {
        if ($this->frequency === Frequencies::Once) {
            $this->delete();
        } else {
            (new Delete($this, $updateType))->handle();
        }
    }

    public function scopeAllowed($query)
    {
        $query->when(
            ! Auth::user()->isAdmin() && ! Auth::user()->isSupervisor(),
            fn ($query) => $query->whereHas(
                'createdBy.person.companies',
                fn ($companies) => $companies->whereIn(
                    'id',
                    Auth::user()->person->companies()->pluck('id')
                )
            )
        );
    }

    public function scopeFor($query, $calendars)
    {
        $query->whereIn('calendar_id', $calendars->pluck('id'));
    }

    public function scopeSequence(Builder $query, int $parentId)
    {
        $query->where(fn ($query) => $query
            ->whereParentId($parentId)
            ->orWhere('id', $parentId));
    }

    public function scopeBetween($query, Carbon $start, Carbon $end)
    {
        $query->whereDate('end_date', '<=', $end)
            ->whereDate('start_date', '>=', $start);
    }

    private function saveOneOrCreateSequence()
    {
        if ($this->frequency === Frequencies::Once) {
            $this->save();
        } else {
            $this->createSequence();
        }
    }

    private function createOrUpdateSequence(int $updateType)
    {
        if ($this->getOriginal('frequency') === Frequencies::Once) {
            $this->createSequence();
        } else {
            $this->updateSequence($updateType);
        }
    }

    private function createSequence()
    {
        DB::transaction(function () {
            $this->save();
            (new Create($this))->handle();
        });
    }

    private function updateSequence(int $updateType)
    {
        DB::transaction(function () use ($updateType) {
            (new Update($this, $updateType))->handle();
            $this->save();
        });
    }
}
