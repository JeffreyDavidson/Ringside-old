<?php

use App\Models\Wrestler;
use Faker\Generator as Faker;

$factory->define(Wrestler::class, function (Faker $faker) {
    $name = $faker->sentence;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'status' => 'Active',
        'hometown' => $faker->city.', '.$faker->state,
        'height' => $faker->numberBetween(63, 84),
        'weight' => $faker->numberBetween(175, 400),
        'signature_move' => $faker->unique()->sentence(3),
        'hired_at' => $faker->dateTimeBetween('-30 years', '-1 year'),
    ];
});

$factory->state(App\Models\Wrestler::class, 'active', function () {
    return ['status' => 'Active'];
});

$factory->state(App\Models\Wrestler::class, 'inactive', function () {
    return ['status' => 'Inactive'];
});

$factory->state(Wrestler::class, 'injured', function () {
    return ['status' => 'Injured'];
});

$factory->state(Wrestler::class, 'suspended', function () {
    return ['status' => 'Suspended'];
});

$factory->state(Wrestler::class, 'retired', function () {
    return ['status' => 'Retired'];
});
