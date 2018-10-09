<?php

use App\Models\Title;
use Carbon\Carbon;
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

$factory->state(Title::class, 'active', ['is_active' => true, 'introduced_at' => Carbon::today()]);
$factory->state(Title::class, 'inactive', ['is_active' => false, 'introduced_at' => Carbon::tomorrow()]);

$factory->afterCreatingState(Title::class, 'retired', function ($title) {
    $title->retire();
});
