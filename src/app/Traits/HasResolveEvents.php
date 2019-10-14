<?php

namespace LaravelEnso\Calendar\app\Traits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

trait HasResolveEvents
{
    public static function getEvents(Request $request) :Collection
    {
        return static::between($request->get('startDate'),$request->get('endDate'))
            ->get();
    }

    public function scopeBetween(Builder $query, $startDate, $endDate)
    {
        $query->whereBetween($this->eventTimeField(), [
            Carbon::parse($startDate),
            Carbon::parse($endDate),
        ]);
    }

    protected function eventTimeField()
    {
        return 'due_date';
    }
}
