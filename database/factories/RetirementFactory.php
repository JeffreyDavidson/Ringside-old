<?php

use Carbon\Carbon;
use App\Models\Retirement;
use Faker\Generator as Faker;

$factory->define(Retirement::class, function (Faker $faker) {
    return [
        'retiree_id' => $faker->randomDigitNotNull,
        'retiree_type' => $faker->sentence,
        'retired_at' => Carbon::now('-2 years'),
        'ended_at' => Carbon::now('-2 days'),
    ];
});
