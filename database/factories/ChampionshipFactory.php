<?php

use App\Models\Championship;
use App\Models\Title;
use App\Models\Wrestler;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Championship::class, function (Faker $faker) {
    return [
        'wrestler_id' => function () {
            return factory(Wrestler::class)->create()->id;
        },
        'title_id' => function () {
            return factory(Title::class)->create()->id;
        },
        'won_on' => Carbon::now(),
    ];
});

$factory->state(Championship::class, 'current', ['won_on' => today()->addMonths(2)]);
$factory->state(Championship::class, 'past', ['won_on' => today()->subMonths(2), 'lost_on' => today()->subMonth()]);
