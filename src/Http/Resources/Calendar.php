<?php

namespace LaravelEnso\Calendar\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Calendar extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'       => $this->getKey(),
            'name'     => $this->name(),
            'color'    => $this->color(),
            'readonly' => $this->readonly(),
        ];
    }
}
