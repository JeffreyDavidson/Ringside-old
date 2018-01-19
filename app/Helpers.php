<?php

use Carbon\Carbon;

function dates(Carbon $from, Carbon $to, $day, $last = false)
{
    $step = $from->copy()->startOfMonth();
    $modification = sprintf($last ? 'last %s of next month' : 'next %s', $day);

    $dates = [];
    while ($step->modify($modification)->lte($to)) {
        if ($step->lt($from)) {
            continue;
        }

        $dates[$step->timestamp] = $step->copy();
    }

    return $dates;
}

function set_active($path, $active = 'active')
{
    return call_user_func_array('Request::is', (array) $path) ? $active : '';
}

function chance(int $percent)
{
    return rand(0, 100) < $percent;
}
