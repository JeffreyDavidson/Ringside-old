<?php

use Carbon\Carbon;
use App\Models\Retirement;
use Faker\Generator as Faker;

$factory->define(Retirement::class, function (Faker $faker) {
    $date = Carbon::today();

    return [
        'retirable_id' => $faker->randomDigitNotNull,
        'retirable_type' => $faker->sentence,
        'retired_at' => $date,
        'ended_at' => $date->addDays(2),
    ];
});
