<?php

use App\Models\Roster\Wrestler;
use Faker\Generator as Faker;

$factory->define(Wrestler::class, function (Faker $faker) {
    $name = $faker->name;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'hometown' => $faker->city.', '.$faker->state,
        'height' => $faker->numberBetween(63, 84),
        'weight' => $faker->numberBetween(175, 400),
        'signature_move' => $faker->unique()->sentence(3),
        'is_active' => true,
        'hired_at' => $faker->dateTimeBetween('-30 years', '-1 year'),
    ];
});

$factory->state(Wrestler::class, 'active', function ($faker) {
    return ['is_active' => true, 'hired_at' => $faker->dateTimeBetween('-30 years', '-1 year')];
});

$factory->afterCreatingState(Wrestler::class, 'inactive', function ($wrestler) {
    $wrestler->deactivate();
});

$factory->afterCreatingState(Wrestler::class, 'suspended', function ($wrestler) {
    $wrestler->suspend();
});

$factory->afterCreatingState(Wrestler::class, 'retired', function ($wrestler) {
    $wrestler->retire();
});

$factory->afterCreatingState(Wrestler::class, 'injured', function ($wrestler) {
    $wrestler->injure();
});
