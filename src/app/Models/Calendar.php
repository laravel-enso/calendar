<?php

namespace LaravelEnso\Calendar\app\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Calendar\app\Services\Request;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\Calendar\app\Services\Frequency;
use LaravelEnso\Rememberable\app\Traits\IsRememberable;
use LaravelEnso\Rememberable\app\Contracts\Rememberable;
use LaravelEnso\Calendar\app\Contracts\Calendar as Contract;

class Calendar extends Model implements Contract, Rememberable
{
    use CreatedBy, IsRememberable;

    protected $fillable = [
        'name', 'color',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function name()
    {
        return $this->name;
    }

    public function color()
    {
        return $this->color;
    }

    public static function getEvents(Request $request): Collection
    {
        $events = Event::between($request)
            ->calendars($request->calendars())
            ->get();

        return (new Frequency($request))->events($events);
    }

    public function readonly()
    {
        return false;
    }
}
