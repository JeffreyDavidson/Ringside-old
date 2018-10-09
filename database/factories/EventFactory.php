<?php

use App\Models\Event;
use App\Models\Venue;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    $name = $faker->unique()->word;

    return [
        'name' => title_case($name),
        'slug' => str_slug($name),
        'date' => $faker->dateTimeBetween('-10 years'),
        'venue_id' => function () {
            return factory(Venue::class)->create()->id;
        },
    ];
});

$factory->state(Event::class, 'scheduled', ['date' => today()->addMonths(2)]);
$factory->state(Event::class, 'past', ['date' => today()->subMonths(2)]);

$factory->afterCreatingState(Event::class, 'archived', function ($event) {
    $event->archive();
});
