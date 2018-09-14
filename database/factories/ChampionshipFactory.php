<?php

use Carbon\Carbon;
use App\Models\Championship;
use Faker\Generator as Faker;

$factory->define(Championship::class, function (Faker $faker) {
    return [
        'wrestler_id' => factory(App\Models\Wrestler::class)->lazy(),
        'title_id' => factory(App\Models\Title::class)->lazy(),
        'won_on' => Carbon::now(),
    ];
});

$factory->state(Championship::class, 'current', ['won_on' => today()->addMonths(2)]);
$factory->state(Championship::class, 'past', ['won_on' => today()->subMonths(2), 'lost_on' => today()->subMonth()]);
