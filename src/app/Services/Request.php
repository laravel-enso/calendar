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
            Carbon::parse($this->request->get('startDate'))->startOfDay();

        return $this->startDate;
    }

    public function endDate(): Carbon
    {
        $this->endDate = $this->endDate ?:
            Carbon::parse($this->request->get('endDate'))->endOfDay();

        return $this->endDate;
    }

    public function calendars()
    {
        return $this->request->get('calendars');
    }
}
