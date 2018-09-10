<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Suspension::class, function (Faker $faker) {
    return [
        'suspendee_id' => $faker->randomNumber(),
        'suspendee_type' => $faker->sentence(),
        'suspended_at' => Carbon::now('-2 years'),
        'ended_at' => Carbon::now('-2 days'),
    ];
});
