<?php

namespace LaravelEnso\Calendar\app\Services;

use Carbon\Carbon;
use Illuminate\Http\Request as LaravelRequest;

class Request
{
    private $request;
    private $startDate;
    private $endDate;

    public function __construct(LaravelRequest $request)
    {
        $this->request = $request;
    }

    public function startDate(): Carbon
    {
        $this->startDate = $this->startDate ?:
            Carbon::parse($this->request->get('startDate'))->setTime(0, 0);

        return $this->startDate;
    }

    public function endDate(): Carbon
    {
        $this->endDate = $this->endDate ?:
            Carbon::parse($this->request->get('endDate'))->setTime(23, 59, 59);

        return $this->endDate;
    }

    public function calendars()
    {
        return $this->request->get('calendars');
    }
}
