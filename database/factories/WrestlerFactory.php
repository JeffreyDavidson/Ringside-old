<?php

use App\Models\Wrestler;
use Faker\Generator as Faker;

$factory->define(Wrestler::class, function (Faker $faker) {
    $name = $faker->sentence;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'is_active' => true,
        'hometown' => $faker->city.', '.$faker->state,
        'height' => $faker->numberBetween(63, 84),
        'weight' => $faker->numberBetween(175, 400),
        'signature_move' => $faker->unique()->sentence(3),
        'hired_at' => $faker->dateTimeBetween('-30 years', '-1 year'),
    ];
});

$factory->state(App\Models\Wrestler::class, 'active', function () {
    return ['is_active' => true];
});

$factory->state(App\Models\Wrestler::class, 'inactive', function () {
    return ['is_active' => false];
});
