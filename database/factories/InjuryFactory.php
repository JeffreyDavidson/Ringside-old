<?php

use Carbon\Carbon;
use App\Models\Injury;
use Faker\Generator as Faker;

$factory->define(Injury::class, function (Faker $faker) {
    return [
        'wrestler_id' => factory(App\Models\Wrestler::class)->lazy(),
        'injured_at' => Carbon::now('-2 years'),
        'healed_at' => Carbon::now('-2 days'),
    ];
});
