<?php

use Faker\Generator as Faker;
use App\Models\Roster\TagTeam;

$factory->define(TagTeam::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'slug' => $faker->slug(),
        'signature_move' => $faker->sentence(),
        'hired_at' => $faker->dateTimeBetween('-30 years', '-1 year'),
    ];
});
