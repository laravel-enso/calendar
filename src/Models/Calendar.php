<?php

namespace LaravelEnso\Calendar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\Calendar\Contracts\Calendar as Contract;
use LaravelEnso\Rememberable\Traits\Rememberable;
use LaravelEnso\TrackWho\Traits\CreatedBy;

class Calendar extends Model implements Contract
{
    use CreatedBy, HasFactory, Rememberable;

    protected $guarded = ['id'];

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

    protected function casts(): array
    {
        return [
            'private' => 'boolean', 'created_by' => 'integer',
        ];
    }
}
