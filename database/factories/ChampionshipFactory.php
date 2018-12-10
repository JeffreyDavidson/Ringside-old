<?php

use Carbon\Carbon;
use App\Models\Title;
use App\Models\Championship;
use Faker\Generator as Faker;
use App\Models\Roster\Wrestler;

$factory->define(Championship::class, function (Faker $faker) {
    return [
        'champion_id' => function () {
            return factory(Wrestler::class)->create()->id;
        },
        'champion_type' => 'App\Models\Wrestler',
        'title_id' => function () {
            return factory(Title::class)->create()->id;
        },
        'won_on' => Carbon::now(),
    ];
});

$factory->state(Championship::class, 'current', ['won_on' => today()->addMonths(2)]);
$factory->state(Championship::class, 'past', ['won_on' => today()->subMonths(2), 'lost_on' => today()->subMonth()]);
