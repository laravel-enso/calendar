<?php

namespace LaravelEnso\Calendar\app\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
// use LaravelEnso\Calendar\app\Enums\Frequencies;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\Helpers\app\Traits\DateAttributes;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;

class Event extends Model implements ProvidesEvent
{
    use CreatedBy, DateAttributes;

    protected $fillable = [
        'title', 'body', 'calendar', 'frequence', 'location', 'lat', 'lng',
        'starts_at', 'ends_at', 'frequence_ends_at', 'is_all_day', 'is_readonly',
    ];

    protected $casts = ['is_all_day' => 'boolean', 'is_readonly' => 'boolean'];

    protected $dates = ['starts_at', 'ends_at', 'frequence_ends_at'];

    public function attendees()
    {
        return $this->belongsToMany(User::class);
    }

    public function attendeeList()
    {
        return $this->attendees->pluck('id');
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function setStartsAtAttribute($value)
    {
        $this->fillDateAttribute('starts_at', $value, 'Y-m-d H:i:s');
    }

    public function setEndsAtAttribute($value)
    {
        $this->fillDateAttribute('ends_at', $value, 'Y-m-d H:i:s');
    }

    public function setRecurrenceEndsAtAttribute($value)
    {
        $this->fillDateAttribute('recurrence_ends_at', $value, 'Y-m-d H:i:s');
    }

    public function updateReminders($reminders)
    {
        $reminders = collect($reminders)
            ->except(function ($reminder) {
                return empty($reminder['remind_at']);
            });

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

    public function calendar()
    {
        return $this->calendar;
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

    public function scopeBetween($query, $startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $query->when($startDate->eq($endDate), function ($query) use ($startDate) {
            $query->whereDate('starts_at', $startDate)
                ->orWhere(function ($query) use ($startDate) {
                    $query->where('starts_at', '<', $startDate)
                        ->where('ends_at', '>=', $startDate);
                });
            // ->orWhere(function ($query) use ($startDate) {
            //     $query->where('frequence', '<>', Frequencies::Once)
            //         ->whereDay('starts_at', $startDate->format('d'));
            // });
        });

        $query->whereBetween('starts_at', [$startDate, $endDate])
            ->orWhere(function ($query) use ($startDate) {
                $query->where('starts_at', '<', $startDate)
                    ->where('ends_at', '>=', $startDate);
            });
        //     ->orWhere(function ($query) use ($startDate, $endDate) {
        //     $query->where('frequence', '<>', Frequencies::Once)
        //         ->where('frequence_ends_at', '>', $startDate)
        //         ->where(function ($query) use ($startDate, $endDate) {
        //             $query->whereIn('frequence', [Frequencies::Daily, Frequencies::Weekdays])
        //                 ->orWhere(function ($query) use ($startDate, $endDate) {
        //                     $query->where('frequence', '=', Frequencies::Weekly)
        //                         ->whereMonth('starts_at', $startDate->format('m'));
        //                 })->orWhere(function ($query) use ($startDate, $endDate) {
        //                     $query->where('frequence', '=', Frequencies::Monthly)
        //                             ->whereDay('starts_at', '>=', $startDate->format('d'))
        //                             ->whereDay('starts_at', '<', $endDate->format('d'))
        //                             ->whereMonth('starts_at', $startDate->format('m'));
        //                 })->orWhere(function ($query) use ($startDate, $endDate) {
        //                     $query->where('frequence', '=', Frequencies::Yearly)
        //                             ->whereDay('starts_at', '>=', $startDate->format('d'))
        //                             ->whereDay('starts_at', '<', $endDate->format('d'))
        //                             ->whereMonth('starts_at', $startDate->format('m'));
        //                 });
        //         });
        // });
    }
}
