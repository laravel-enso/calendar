<?php

namespace LaravelEnso\Calendar\app\Services\Frequency;

use Illuminate\Support\Facades\DB;
use LaravelEnso\Calendar\app\Models\Event;
use LaravelEnso\Calendar\app\Enums\UpdateType;

class Delete extends Frequency
{
    public function handle($updateType)
    {
        if ($this->parent()->events->isEmpty()) {
            return;
        }

        if ($updateType === UpdateType::Single) {
            return $this->isParent()
                ? $this->changeParent()
                : null;
        }

        Event::sequence($this->parent()->id)
            ->when($updateType === UpdateType::Futures, function ($query) {
                $query->where('starts_date', '>', $this->event->starts_date);
            })->delete();
    }

    protected function changeParent()
    {
        $nextEventId = $this->parent()->events
            ->sortBy('starts_date')->first()->id;

        Event::whereParentId($this->parent()->id)->update([
            'parent_id' => DB::raw("IF(id = {$nextEventId},NULL,{$nextEventId})"),
        ]);
    }
}
