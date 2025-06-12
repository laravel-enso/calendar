<?php

namespace LaravelEnso\Calendar\Enums;

use LaravelEnso\Enums\Contracts\Frontend;
use LaravelEnso\Enums\Contracts\Mappable;

enum UpdateType: int implements Mappable, Frontend
{
    case OnlyThis = 1;
    case ThisAndFuture = 2;
    case All = 3;

    public function map(): string
    {
        return match ($this) {
            self::OnlyThis => 'Only This',
            self::ThisAndFuture => 'This And Future',
            self::All => 'All',
        };
    }

    public static function registerBy(): string
    {
        return 'eventUpdateType';
    }

    public static function forParent()
    {
        return collect([self::OnlyThis, self::ThisAndFuture])
            ->map(fn ($case) => (object) ['id' => $case->value, 'name' => $case->map()]);
    }
}
