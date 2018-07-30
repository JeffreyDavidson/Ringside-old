<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Wrestler::class, function (Faker $faker) {
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

$factory->state(App\Models\Wrestler::class, 'active', ['is_active' => 1, 'hired_at' => Carbon::today()]);
$factory->state(App\Models\Wrestler::class, 'inactive', ['is_active' => 0, 'hired_at' => Carbon::tomorrow()]);
$factory->afterCreatingState(App\Models\Wrestler::class, 'suspended', function ($wrestler) {
    $wrestler->suspend();
});
$factory->afterCreatingState(App\Models\Wrestler::class, 'retired', function ($wrestler) {
    $wrestler->retire();
});
$factory->afterCreatingState(App\Models\Wrestler::class, 'injured', function ($wrestler) {
    $wrestler->injure();
});
