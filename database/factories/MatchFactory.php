<?php

use App\Models\Event;
use App\Models\Match;
use App\Models\MatchType;
use Faker\Generator as Faker;

$factory->define(Match::class, function (Faker $faker) {
    return [
        'event_id' => function () {
            return factory(Event::class)->create()->id;
        },
        'match_type_id' => function () {
            return factory(MatchType::class)->create()->id;
        },
        'preview' => $faker->paragraphs(3, true),
    ];
});
