<?php

use App\Models\Match;
use Faker\Generator as Faker;

$factory->define(Match::class, function (Faker $faker) {
    return [
        'event_id' => factory(App\Models\Event::class)->lazy(),
        'match_type_id' => factory(App\Models\MatchType::class)->lazy(),
        'preview' => $faker->paragraphs(3, true),
        'result' => $faker->paragraphs(3, true),
    ];
});
