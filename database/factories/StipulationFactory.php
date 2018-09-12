<?php

use App\Models\Stipulation;
use Faker\Generator as Faker;

$factory->define(Stipulation::class, function (Faker $faker) {
    $name = $faker->sentence;

    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});
