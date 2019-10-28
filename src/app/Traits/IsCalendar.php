<?php

namespace LaravelEnso\Calendar\app\Traits;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use LaravelEnso\Calendar\app\Services\Request;

trait IsCalendar
{
    public function getKey()
    {
        return $this->name();
    }

    public function events(Carbon $startDate, Carbon $endDate): Collection
    {
        return (new static())->getQuery()->whereBetween(
            (new static())->eventDateField(),
            [$startDate, $endDate]
        )->get();
    }

    public function readonly(): bool
    {
        return true;
    }

    private function eventDateField()
    {
        return property_exists($this, 'date_field')
            ? $this->date_field
            : 'created_at';
    }

    private function getQuery()
    {
        return property_exists($this, 'model')
            ? $this->model::query()
            : $this->query();
    }
}
