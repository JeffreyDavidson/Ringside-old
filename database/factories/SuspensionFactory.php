<?php

use App\Models\Suspension;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Suspension::class, function (Faker $faker) {
    $date = Carbon::today();

    return [
        'suspendee_id' => $faker->randomNumber(),
        'suspendee_type' => $faker->sentence(),
        'suspended_at' => $date,
        'ended_at' => $date->addDays(2),
    ];
});
