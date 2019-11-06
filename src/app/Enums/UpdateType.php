<?php

namespace LaravelEnso\Calendar\app\Enums;

use LaravelEnso\Enums\app\Services\Enum;

class UpdateType extends Enum
{
    const Single = 'single';
    const Futures = 'futures';
    const All = 'all';

    protected static function attributes()
    {
        return [
            static::Single => 'Only this event',
            static::Futures => 'This and future events',
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
