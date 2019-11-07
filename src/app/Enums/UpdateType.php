<?php

namespace LaravelEnso\Calendar\app\Enums;

use LaravelEnso\Enums\app\Services\Enum;

class UpdateType extends Enum
{
    const OnlyThisEvent = 1;
    const ThisAndFutureEvents = 2;
    const All = 3;

    protected static function attributes()
    {
        return [
            static::OnlyThisEvent => 'Only This Event',
            static::ThisAndFutureEvents => 'This And Future Events',
            static::All => 'All',
        ];
    }

    public static function forParent()
    {
        return static::select()->reject(function ($updateType) {
            return $updateType->id === static::All;
        });
    }
}
