<?php

namespace LaravelEnso\Calendar\app\Http\Responses;

use Illuminate\Http\Request;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;
use LaravelEnso\Calendar\app\Contracts\ResolvesEvents;

class Events
{
    private static $resolvers = [
        ResolvesEvents::class
    ];

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get()
    {
        return $this->events();
    }

    public static function addResolver($resolver)
    {
        self::$resolvers[] = $resolver;
    }

    private function events()
    {
        return collect(self::$resolvers)
            ->reduce(function ($events, $resolver) {
                return $events->concat($this->resolveEvents($resolver));
            }, collect());
    }

    private function resolveEvents($resolver)
    {
        return app()->make($resolver)->getEvents($this->request)
            ->filter(function (ProvidesEvent $model) {
                return $model;
            });
    }
}
