<?php

namespace LaravelEnso\Calendar\app\Http\Responses;

use Illuminate\Http\Request;
use LaravelEnso\Calendar\app\Contracts\ProvidesEvent;
use LaravelEnso\Calendar\app\Contracts\ResolvesEvents;

class Events
{
    private static $resolvers = [];

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get()
    {
        return $this->baseEvents()
            ->concat($this->localEvents());
    }

    public static function addResolver($resolver)
    {
        self::$resolvers[] = $resolver;
    }

    private function baseEvents()
    {
        return app()->make(ResolvesEvents::class)
            ->get($this->request);
    }

    private function localEvents()
    {
        return collect(self::$resolvers)
            ->reduce(function ($events, $resolver) {
                $events = $events->concat(
                    $this->resolve(new $resolver)
                        ->filter(function (ProvidesEvent $model) {
                            return $model;
                        })
                );

                return $events;
            }, collect());
    }

    private function resolve(ResolvesEvents $resolver)
    {
        return $resolver->get($this->request);
    }
}
