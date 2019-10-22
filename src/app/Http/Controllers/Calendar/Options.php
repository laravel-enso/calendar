<?php

namespace LaravelEnso\Calendar\app\Http\Controllers\Calendar;

use Illuminate\Routing\Controller;
use LaravelEnso\Calendar\app\Models\Calendar;
use LaravelEnso\Select\app\Traits\OptionsBuilder;

class Options extends Controller
{
    use OptionsBuilder;

    protected $queryAttributes = ['name'];
    protected $model = Calendar::class;
}
