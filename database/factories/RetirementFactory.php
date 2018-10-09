<?php

use App\Models\Retirement;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Retirement::class, function (Faker $faker) {
    $date = Carbon::today();

    return [
        'retiree_id' => $faker->randomDigitNotNull,
        'retiree_type' => $faker->sentence,
        'retired_at' => $date,
        'ended_at' => $date->addDays(2),
    ];
});
