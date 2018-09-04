<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Suspension::class, function (Faker $faker) {
    return [
        'wrestler_id' => factory(App\Models\Wrestler::class)->lazy(),
        'suspended_at' => Carbon::now('-2 years'),
        'ended_at' => Carbon::now('-2 days'),
    ];
});