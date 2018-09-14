<?php

use App\Models\MatchType;
use Faker\Generator as Faker;

$factory->define(MatchType::class, function (Faker $faker) {
    $name = $faker->sentence;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'number_of_sides' => 2,
        'total_competitors' => 2,
    ];
});
