<?php

namespace LaravelEnso\Calendar\app\Traits;

use Illuminate\Support\Collection;
use LaravelEnso\Calendar\app\Services\Request;

trait IsCalendar
{
    public function getKey()
    {
        return $this->name();
    }

    public static function events(Request $request): Collection
    {
        return (new static())->getQuery()->whereBetween(
            (new static())->eventDateField(),
            [$request->startDate(), $request->endDate()]
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
