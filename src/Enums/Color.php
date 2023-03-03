<?php

namespace LaravelEnso\Calendar\Enums;

use LaravelEnso\Enums\Traits\Random;

enum Color: string
{
    use Random;

    case Red = 'red';
    case Blue = 'blue';
    case Green = 'green';
    case Yellow = 'yellow';
    case Pink = 'pink';
    case Purple = 'purple';
    case Orange = 'orange';
    case Brown = 'brown';
}
