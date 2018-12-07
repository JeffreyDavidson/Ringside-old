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

$factory->state(TagTeam::class, 'active', function ($faker) {
    return ['is_active' => true, 'hired_at' => $faker->dateTimeBetween('-30 years', '-1 year')];
});

$factory->afterCreatingState(TagTeam::class, 'inactive', function ($tagteam) {
    $tagteam->deactivate();
});

$factory->afterCreatingState(TagTeam::class, 'retired', function ($tagteam) {
    $tagteam->retire();
});
