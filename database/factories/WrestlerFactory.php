<?php

use App\Models\Wrestler;
use App\Models\WrestlerStatus;
use Faker\Generator as Faker;

$factory->define(Wrestler::class, function (Faker $faker) {
    $name = $faker->sentence;
    return [
        'name' => $name,
        'slug' => str_slug($name),
        'status_id' => WrestlerStatus::ACTIVE,
        'hometown' => $faker->city . ', ' . $faker->state,
        'height' => $faker->numberBetween(63, 84),
        'weight' => $faker->numberBetween(175, 400),
        'signature_move' => $faker->unique()->sentence(3),
        'hired_at' => $faker->dateTimeBetween('-30 years','-1 year'),
    ];
});

$factory->state(\Wrestler::class, 'active', function () {
    return ['status_id' => WrestlerStatus::ACTIVE];
});

$factory->state(App\Models\Wrestler::class, 'inactive', function () {
    return ['status_id' => WrestlerStatus::INACTIVE];
});

$factory->state(Wrestler::class, 'injured', function () {
    return ['status_id' => WrestlerStatus::INJURED];
});

$factory->state(Wrestler::class, 'suspended', function () {
    return ['status_id' => WrestlerStatus::SUSPENDED];
});

$factory->state(Wrestler::class, 'retired', function () {
    return ['status_id' => WrestlerStatus::RETIRED];
});
