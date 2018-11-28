<?php

use App\Models\Title;
use Faker\Generator as Faker;

$factory->define(Title::class, function (Faker $faker) {
    $name = $faker->sentence;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'is_active' => true,
        'introduced_at' => $faker->dateTimeBetween('-30 years', '-1 year'),
    ];
});

$factory->state(Title::class, 'active', function ($faker) {
    return ['is_active' => true, 'introduced_at' => $faker->dateTimeBetween('-30 years', '-1 year')];
});

$factory->afterCreatingState(Title::class, 'inactive', function ($title) {
    $title->deactivate();
});

$factory->afterCreatingState(Title::class, 'retired', function ($title) {
    $title->retire();
});
