<?php

use Faker\Generator as Faker;
use App\Models\Event;

$factory->define(Event::class, function (Faker $faker) {
    $name = $faker->sentence;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'date' => $faker->dateTimeBetween('-10 years'),
        'venue_id' => factory(App\Models\Venue::class)->lazy(),
    ];
});

$factory->state(Event::class, 'scheduled', ['date' => today()->addMonths(2)]);
$factory->state(Event::class, 'past', ['date' => today()->subMonths(2)]);

$factory->afterCreatingState(Event::class, 'archived', function ($event) {
    $event->archive();
});
