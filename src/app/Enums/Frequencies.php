<?php

namespace LaravelEnso\Calendar\app\Enums;

use LaravelEnso\Helpers\app\Classes\Enum;

class Frequencies extends Enum
{
    const Once = 1;
    const Daily = 2;
    const Weekdays = 3;
    const Weekly = 4;
    const Monthly = 5;
    const Yearly = 6;
}
