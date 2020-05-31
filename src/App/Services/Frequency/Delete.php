<?php

namespace LaravelEnso\Calendar\App\Services\Frequency;

use LaravelEnso\Calendar\App\Enums\UpdateType;
use LaravelEnso\Calendar\App\Models\Event;
use LaravelEnso\Calendar\App\Services\Sequence;

class Delete
{
    protected Event $event;
    protected $updateType;

    public function __construct(Event $event, int $updateType)
    {
        $this->event = $event;
        $this->updateType = $updateType;
    }

    public function handle()
    {
        switch ($this->updateType) {
            case UpdateType::All:
                $this->all();
                break;
            case UpdateType::ThisAndFuture:
                $this->currentAndFuture();
                break;
            case UpdateType::OnlyThis:
                $this->current();
                break;
        }
    }

    private function all()
    {
        Event::sequence($this->event->parent_id ?? $this->event->id)->delete();
    }

    private function currentAndFuture()
    {
        (new Sequence($this->event))->break();

        Event::sequence($this->event->parent_id ?? $this->event->id)
            ->where('start_date', '>=', $this->event->start_date->format('Y-m-d'))
            ->delete();
    }

    private function current()
    {
        (new Sequence($this->event))->extract();

        $this->event->delete();
    }
}
