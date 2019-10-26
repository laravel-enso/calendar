<?php

namespace LaravelEnso\Calendar\app\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Calendar\app\Services\Request;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\Calendar\app\Services\Frequency;
use LaravelEnso\Rememberable\app\Traits\Rememberable;
use LaravelEnso\Calendar\app\Contracts\Calendar as Contract;

class Calendar extends Model implements Contract
{
    use CreatedBy, Rememberable;

    protected $fillable = ['name', 'color', 'private'];

    protected $casts = ['private' => 'boolean'];

    // public static function events(Request $request): Collection
    // {
    //     return (new Frequency($request))->events(
    //         Event::calendars($request->calendars())
    //     );
    // }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function color(): string
    {
        return $this->color;
    }

    public function private(): bool
    {
        return $this->private;
    }

    public function readonly(): bool
    {
        return false;
    }    
}
