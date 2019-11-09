<?php

namespace LaravelEnso\Calendar\app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Reminder extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'scheduled_at' => $this->scheduled_at,
        ];
    }
}
