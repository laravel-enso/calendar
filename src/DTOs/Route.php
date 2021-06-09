<?php

namespace LaravelEnso\Calendar\DTOs;

class Route
{
    public function __construct(
        private string $name,
        private array $params = [],
    ) {
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'params' => $this->params,
        ];
    }
}
