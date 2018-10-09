<?php

use App\Models\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    $name = $faker->word;

    return [
        'name' => title_case($name),
        'slug' => str_slug($name),
    ];
});
