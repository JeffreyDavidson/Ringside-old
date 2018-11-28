<?php

use Carbon\Carbon;
use App\Models\Suspension;
use Faker\Generator as Faker;

$factory->define(Suspension::class, function (Faker $faker) {
    $date = Carbon::today();

    return [
        'suspendable_id' => $faker->randomNumber(),
        'suspendable_type' => $faker->sentence(),
        'suspended_at' => $date,
        'ended_at' => $date->addDays(2),
    ];
});
