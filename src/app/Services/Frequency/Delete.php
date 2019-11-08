<?php

namespace LaravelEnso\Calendar\app\Services\Frequency;

use LaravelEnso\Calendar\app\Enums\UpdateType;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Services\Sequence;

class Delete extends Frequency
{
    protected $rootEvent;
    protected $updateType;

    public function handle($updateType)
    {
        $this->updateType = $updateType;

        $this->init()
            ->break()
            ->delete();
    }

    private function init()
    {
        $this->rootEvent = $this->updateType === UpdateType::All
            ? $this->parent()
            : $this->event;

        return $this;
    }

    private function break()
    {
        switch ($this->updateType) {
            case UpdateType::OnlyThisEvent:
                (new Sequence($this->event))->break($this->event, 1);
                break;
            case UpdateType::ThisAndFutureEvents:
                (new Sequence($this->event))->break($this->event);
        }

        return $this;
    }

    private function delete()
    {
        Event::sequence($this->rootEvent->id)->delete();
    }
}
