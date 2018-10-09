<?php

use App\Models\MatchType;
use Faker\Generator as Faker;

$factory->define(MatchType::class, function (Faker $faker) {
    $name = $faker->unique()->word;
    $number = $faker->randomDigitNotNull;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'number_of_sides' => $number,
        'total_competitors' => $number,
    ];
});
