<?php

namespace LaravelEnso\Calendar\Enums;

use Illuminate\Support\Collection;
use LaravelEnso\Enums\Contracts\Select;
use LaravelEnso\Enums\Traits\Select as Options;

enum Colors: string implements Select
{
    use Options;

    case Red = 'red';
    case Blue = 'blue';
    case Green = 'green';
    case Yellow = 'yellow';
    case Purple = 'purple';
    case Orange = 'orange';
    case Brown = 'brown';
    case Black = 'black';
    case White = 'white';
    case Pink = 'pink';
    case Aquamarine = 'aquamarine';
    case Teal = 'teal';
    case Gold = 'gold';
    case Indigo = 'indigo';
    case Cyan = 'cyan';
    case Lime = 'lime';
    case Violet = 'violet';
    case Magenta = 'magenta';
    case Beige = 'beige';
    case Salmon = 'salmon';
    case Coral = 'coral';
    case Navy = 'navy';
    case Olive = 'olive';
    case Turquoise = 'turquoise';
    case Maroon = 'maroon';
    case Lavender = 'lavender';
    case Khaki = 'khaki';
    case Crimson = 'crimson';
    case Orchid = 'orchid';

    public static function values() : Collection
    {
        return Collection::wrap(self::cases())
                ->map(fn ($case) => $case->value);
    }
}
