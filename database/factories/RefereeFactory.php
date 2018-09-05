<?php

use App\Models\Referee;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Referee::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'is_active' => true,
        'hired_at' => $faker->dateTimeBetween('-30 years', '-1 year'),
    ];
});

$factory->state(Referee::class, 'active', ['is_active' => true, 'hired_at' => Carbon::today()]);
$factory->state(Referee::class, 'inactive', ['is_active' => false, 'hired_at' => Carbon::tomorrow()]);

$factory->afterCreatingState(Referee::class, 'suspended', function ($referee) {
    $referee->suspend();
});

$factory->afterCreatingState(Referee::class, 'retired', function ($referee) {
    $referee->retire();
    $referee->deactivate();
});
