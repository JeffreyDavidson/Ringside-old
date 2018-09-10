<?php

use App\Models\Manager;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Manager::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'is_active' => true,
        'hired_at' => $faker->dateTimeBetween('-30 years', '-1 year'),
    ];
});

$factory->state(Manager::class, 'active', ['is_active' => true, 'hired_at' => Carbon::today()]);
$factory->state(Manager::class, 'inactive', ['is_active' => false, 'hired_at' => Carbon::tomorrow()]);

$factory->afterCreatingState(Manager::class, 'suspended', function ($manager) {
    $manager->suspend();
});

$factory->afterCreatingState(Manager::class, 'retired', function ($manager) {
    $manager->retire();
});
