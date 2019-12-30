<?php

namespace LaravelEnso\Calendar\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use LaravelEnso\Calendar\App\Contracts\Routable;
use LaravelEnso\Calendar\App\Models\Event as EventModel;

class Event extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->getKey(),
            'title' => $this->title().' '.$this->parentId(),
            'parentId' => $this->parentId(),
            'isLast' => $this->isLast(),
            'body' => $this->body(),
            'start' => $this->start()->format('Y-m-d H:i'),
            'end' => $this->end()->format('Y-m-d H:i'),
            'location' => $this->location(),
            'frequency' => $this->frequency(),
            'recurrenceEnds' => optional($this->recurrenceEnds())
                ->format(config('enso.config.dateFormat')),
            'allDay' => $this->allDay(),
            'readonly' => $this->readonly(),
            'class' => $this->getCalendar()->color(),
            'route' => $this->route(),
            'deletable' => ! $this->readonly(),
            'resizable' => ! $this->readonly(),
        ];
    }

    protected function parentId()
    {
        return $this->resource instanceof EventModel
            ? $this->parent_id
            : null;
    }

    protected function isLast()
    {
        return $this->parentId()
            ? EventModel::cacheGet($this->parentId())
                ->recurrence_ends_at->eq($this->start_date)
            : false;
    }

    private function route()
    {
        return $this->resource instanceof Routable
            ? $this->resource->route()->toArray()
            : null;
    }
}
