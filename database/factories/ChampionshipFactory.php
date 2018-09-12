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
