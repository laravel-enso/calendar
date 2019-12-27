<?php

namespace LaravelEnso\Calendar\app\Enums;

use LaravelEnso\Enums\app\Services\Enum;

class UpdateType extends Enum
{
    public const OnlyThisEvent = 1;
    public const ThisAndFutureEvents = 2;
    public const All = 3;

    protected static $data = [
        self::OnlyThisEvent => 'Only This Event',
        self::ThisAndFutureEvents => 'This And Future Events',
        self::All => 'All',
    ];

    public static function forParent()
    {
        return static::select()
            ->reject(fn ($updateType) => $updateType->id === static::All);
    }
}
