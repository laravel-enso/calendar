<?php

namespace LaravelEnso\Calendar\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use LaravelEnso\Calendar\app\Contracts\RoutableEvent;

class Event extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->getKey(),
            'title' => $this->title(),
            'body' => $this->body(),
            'start' => $this->start()->format('Y-m-d H:i'),
            'end' => $this->end()->format('Y-m-d H:i'),
            'location' => $this->location(),
            'frequence' => $this->frequence(),
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

    private function route()
    {
        return $this->resource instanceof RoutableEvent
            ? $this->resource->route()->toArray()
            : null;
    }
}
