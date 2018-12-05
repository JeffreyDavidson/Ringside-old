<?php

use App\Models\Roster\Referee;
use Faker\Generator as Faker;

$factory->define(Referee::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'is_active' => true,
        'hired_at' => $faker->dateTimeBetween('-30 years', '-1 year'),
    ];
});

$factory->state(Referee::class, 'active', function ($faker) {
    return ['is_active' => true, 'hired_at' => $faker->dateTimeBetween('-30 years', '-1 year')];
});

$factory->afterCreatingState(Referee::class, 'inactive', function ($wrestler) {
    $wrestler->deactivate();
});

$factory->afterCreatingState(Referee::class, 'suspended', function ($referee) {
    $referee->suspend();
});

$factory->afterCreatingState(Referee::class, 'retired', function ($referee) {
    $referee->retire();
});
