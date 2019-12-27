<?php

namespace LaravelEnso\Calendar\app\DTOs;

class Route
{
    private string $name;
    private array $params;

    public function __construct(string $name, array $params = [])
    {
        $this->name = $name;
        $this->params = $params;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'params' => $this->params,
        ];
    }
}
