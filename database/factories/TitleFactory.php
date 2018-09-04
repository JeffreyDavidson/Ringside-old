<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Title::class, function (Faker $faker) {
    $name = $faker->sentence;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'is_active' => true,
        'introduced_at' => Carbon::now()->subMonths(8),
    ];
});

$factory->state(App\Models\Title::class, 'active', ['is_active' => true]);
$factory->state(App\Models\Title::class, 'inactive', ['is_active' => false]);

$factory->afterCreatingState(App\Models\Title::class, 'retired', function ($title) {
    $title->retire();
    $title->deactivate();
});

