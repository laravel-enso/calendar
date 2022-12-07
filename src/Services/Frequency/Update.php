<?php

namespace LaravelEnso\Calendar\Services\Frequency;

use LaravelEnso\Calendar\Enums\UpdateType;
use LaravelEnso\Calendar\Models\Event;
use LaravelEnso\Calendar\Services\Sequence;

class Update
{
    public function __construct(
        protected Event $event,
        protected UpdateType $updateType
    ) {
    }

    public function handle()
    {
        if ($this->isSingular()) {
            $this->extract();
        } else {
            $this->sync();
        }
    }

    private function extract()
    {
        (new Sequence($this->event))->extract();

        $this->event->save();
    }

    private function sync()
    {
        if ($this->shouldRegenerate()) {
            $this->regenerate();
        } else {
            $this->update();
        }
    }

    private function regenerate()
    {
        Event::sequence($this->event->parent_id ?? $this->event->id)
            ->where('start_date', '>', $this->event->getOriginal('start_date'))
            ->delete();

        $this->event->parent_id = null;
        $this->event->store();
    }

    private function update()
    {
        $dirty = $this->event->getDirty();
        unset($dirty['parent_id']);

        if ($this->updateType === UpdateType::ThisAndFuture) {
            $this->currentAndFuture($dirty);
        } else {
            $this->all($dirty);
        }
    }

    private function currentAndFuture(array $dirty)
    {
        if ($this->event->parent_id) {
            (new Sequence($this->event))->break();
        }

        $this->event->events()->update($dirty);
    }

    private function all(array $dirty)
    {
        Event::sequence($this->event->parent_id ?? $this->event->id)
            ->update($dirty);
    }

    private function shouldRegenerate(): bool
    {
        return $this->event->isDirty(['frequency', ...$this->event->getDates()]);
    }

    private function isSingular(): bool
    {
        return $this->updateType === UpdateType::OnlyThis;
    }
}
