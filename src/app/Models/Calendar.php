<?php

namespace LaravelEnso\Calendar\app\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Calendar\app\Contracts\Calendar as Contract;
use LaravelEnso\Rememberable\app\Traits\Rememberable;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;

class Calendar extends Model implements Contract
{
    use CreatedBy, Rememberable;

    protected $fillable = ['name', 'color', 'private'];

    protected $casts = ['private' => 'boolean'];

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
