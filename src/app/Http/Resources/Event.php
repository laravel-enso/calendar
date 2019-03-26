<?php

namespace LaravelEnso\Calendar\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use LaravelEnso\Calendar\app\Enums\Classes;
use LaravelEnso\TrackWho\app\Http\Resources\TrackWho;

class Event extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->getKey(),
            'title'          => $this->title(),
            'body'           => $this->body(),
            'start'          => $this->start()->format('Y-m-d H:i'),
            'end'            => $this->end()->format('Y-m-d H:i'),
            'location'       => $this->location(),
            'calendar'       => $this->calendar(),
            'frequence'      => $this->frequence(),
            'recurrenceEnds' => optional($this->recurrenceEnds())
                ->format(config('enso.config.dateFormat')),
            'class'    => Classes::get($this->calendar()),
            'allDay'   => $this->allDay(),
            'readonly' => $this->readonly(),
            // 'attendees' => Attendee::collection($this->whenLoaded('users')),
            // 'reminders' => Reminder::collection($this->whenLoaded('reminders')),
            'owner' => new TrackWho($this->whenLoaded('createdBy')),
        ];
    }
}
