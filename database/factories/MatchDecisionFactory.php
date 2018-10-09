<?php

use App\Models\MatchDecision;
use Faker\Generator as Faker;

$factory->define(MatchDecision::class, function (Faker $faker) {
    $name = $faker->word;

    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});
