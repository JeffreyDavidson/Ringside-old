<?php

use App\Models\Venue;
use Faker\Generator as Faker;

$factory->define(Venue::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'postcode' => substr($faker->postcode, 0, 5),
    ];
});
