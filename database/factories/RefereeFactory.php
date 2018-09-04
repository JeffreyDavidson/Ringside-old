<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Referee::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'hired_at' => Carbon::parse('-2 weeks'),
    ];
});
