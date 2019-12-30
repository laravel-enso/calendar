<?php

namespace LaravelEnso\Calendar\App\Services\Frequency;

use LaravelEnso\Calendar\App\Enums\UpdateType;
use LaravelEnso\Calendar\App\Models\Event;
use LaravelEnso\Calendar\App\Services\Sequence;

class Delete extends Frequency
{
    protected Event $rootEvent;
    protected int $updateType;

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
                break;
        }

        return $this;
    }

    private function delete()
    {
        Event::sequence($this->rootEvent->id)->delete();
    }
}
