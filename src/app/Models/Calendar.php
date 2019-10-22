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

    protected $fillable = ['name', 'color'];

    public static function events(Request $request): Collection
    {
        return (new Frequency($request))->events(
            Event::calendars($request->calendars())
        );
    }

    public function name(): string
    {
        return $this->name;
    }

    public function color(): string
    {
        return $this->color;
    }

    public function readonly(): bool
    {
        return false;
    }
}
