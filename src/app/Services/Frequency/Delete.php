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

        if ($updateType === UpdateType::All) {
            Event::whereParentId($this->parent()->id)
                ->orWhere('id', $this->parent()->id)
                ->delete();

            return;
        }

        if ($this->isParent() && $updateType === UpdateType::Single) {
            $this->changeParent();
        }
    }

    protected function changeParent()
    {
        $nextEventId = $this->parent()->events->first()->id;

        Event::whereParentId($this->parent()->id)->update([
            'parent_id' => DB::raw("IF(id = {$nextEventId},NULL,{$nextEventId})"),
        ]);
    }
}
