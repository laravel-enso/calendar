<?php

namespace LaravelEnso\Calendar\app\DTOs;

class Route
{
    private $name;
    private $params;

    public function __construct($name, $params = [])
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
