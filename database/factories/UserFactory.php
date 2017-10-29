<?php

use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'role_id' => $faker->numberBetween(1, 4),
        'remember_token' => str_random(10),
    ];
});

$factory->state(App\Models\User::class, 'basic', function (Faker $faker) {
    return ['role_id' => 1];
});

$factory->state(App\Models\User::class, 'editor', function (Faker $faker) {
    return ['role_id' => 2];
});

$factory->state(App\Models\User::class, 'admin', function (Faker $faker) {
    return ['role_id' => 3];
});

$factory->state(App\Models\User::class, 'super-admin', function (Faker $faker) {
    return ['role_id' => 4];
});
