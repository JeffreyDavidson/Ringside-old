<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Event::class, function (Faker $faker) {
    $name = $faker->sentence;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'date' => $faker->dateTimeBetween('-10 years'),
        'venue_id' => factory(App\Models\Venue::class)->lazy(),
    ];
});

$factory->state(App\Models\Event::class, 'scheduled', ['date' => Carbon::today()->addMonths(2)]);
$factory->state(App\Models\Event::class, 'past', ['date' => Carbon::today()->subMonths(2)]);

$factory->afterCreatingState(App\Models\Wrestler::class, 'archived', function ($wrestler) {
    $wrestler->archive();
});
