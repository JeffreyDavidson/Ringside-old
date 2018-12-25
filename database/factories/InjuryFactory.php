<?php

use Carbon\Carbon;
use App\Models\Injury;
use App\Models\Roster\Wrestler;
use Faker\Generator as Faker;

$factory->define(Injury::class, function (Faker $faker) {
    $date = Carbon::today();

    return [
        'wrestler_id' => function () {
            return factory(Wrestler::class)->create()->id;
        },
        'injured_at' => $date,
        'healed_at' => $date->addDays(2),
    ];
});
