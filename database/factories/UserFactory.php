<?php

use App\Models\Role;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $faker->password,
        'role_id' => function () {
            return factory(Role::class)->create()->id;
        },
        'remember_token' => str_random(10),
    ];
});

$factory->state(User::class, 'user', ['role_id' => 1]);
$factory->state(User::class, 'editor', ['role_id' => 2]);
$factory->state(User::class, 'admin', ['role_id' => 3]);
$factory->state(User::class, 'super-admin', ['role_id' => 4]);
