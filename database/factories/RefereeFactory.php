<?php

use Carbon\Carbon;
use App\Models\Referee;
use Faker\Generator as Faker;

$factory->define(Referee::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});
