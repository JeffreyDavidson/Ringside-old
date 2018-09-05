<?php

use App\Models\Wrestler;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Wrestler::class, function (Faker $faker) {
    $name = $faker->sentence;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'hometown' => $faker->city . ', ' . $faker->state,
        'height' => $faker->numberBetween(63, 84),
        'weight' => $faker->numberBetween(175, 400),
        'signature_move' => $faker->unique()->sentence(3),
        'is_active' => true,
        'hired_at' => $faker->dateTimeBetween('-30 years', '-1 year'),
    ];
});

$factory->state(Wrestler::class, 'active', ['is_active' => true, 'hired_at' => Carbon::today()]);
$factory->state(Wrestler::class, 'inactive', ['is_active' => false, 'hired_at' => Carbon::tomorrow()]);

$factory->afterCreatingState(Wrestler::class, 'suspended', function ($wrestler) {
    $wrestler->suspend();
});

$factory->afterCreatingState(Wrestler::class, 'retired', function ($wrestler) {
    $wrestler->retire();
    $wrestler->deactivate();
});

$factory->afterCreatingState(Wrestler::class, 'injured', function ($wrestler) {
    $wrestler->injure();
});
