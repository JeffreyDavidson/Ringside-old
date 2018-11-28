<?php

use App\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    $name = $faker->unique()->word;

    return [
        'name' => title_case($name),
        'slug' => str_slug($name),
    ];
});
