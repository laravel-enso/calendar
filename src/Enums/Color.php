<?php

namespace LaravelEnso\Calendar\Enums;

use LaravelEnso\Enums\Traits\Enum;

enum Color: string
{
    use Enum;

    case Red = 'red';
    case Blue = 'blue';
    case Green = 'green';
    case Yellow = 'yellow';
    case Pink = 'pink';
    case Purple = 'purple';
    case Orange = 'orange';
    case Brown = 'brown';
}
