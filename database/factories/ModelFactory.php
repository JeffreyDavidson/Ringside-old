<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Carbon\Carbon;

$factory->define(App\Models\Permission::class, function (Faker\Generator $faker) {
    $name = $faker->sentence;
    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});

$factory->define(App\Models\Role::class, function (Faker\Generator $faker) {
    $name = $faker->sentence;
    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});

$factory->define(App\Models\WrestlerStatus::class, function (Faker\Generator $faker) {
    return ['name' => $faker->sentence(3)];
});

$factory->define(App\Models\Injury::class, function (Faker\Generator $faker) {
    return [
        'wrestler_id' => function () {
            return factory(App\Models\Wrestler::class)->create()->id;
        },
        'injured_at' => Carbon::now('-2 years'),
        'healed_at' => Carbon::now('-2 days'),
    ];
});

$factory->define(App\Models\Retirement::class, function (Faker\Generator $faker) {
    return [
        'wrestler_id' => function () {
            return factory(App\Models\Wrestler::class)->create()->id;
        },
        'retired_at' => Carbon::now('-2 years'),
        'ended_at' => Carbon::now('-2 days'),
    ];
});

$factory->define(App\Models\Manager::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName
    ];
});

$factory->define(App\Models\Title::class, function (Faker\Generator $faker) {
    $name = $faker->sentence;
    return [
        'name' => $name,
        'slug' => str_slug($name),
        'introduced_at' => $faker->date(),
    ];
});

$factory->state(App\Models\Title::class, 'retired', function ($faker) {
    return [
        'retired_at' => function (array $title) use ($faker) {
            return $faker->dateTimeBetween($title['introduced_at']);
        },
    ];
});

$factory->define(App\Models\Match::class, function (Faker\Generator $faker) {
    return [
        'match_number' => $faker->randomDigitNotNull,
        'match_type_id' => function () {
            return factory(App\Models\MatchType::class)->create()->id;
        },
        'preview' => $faker->paragraphs(3, true),
    ];
});

$factory->define(App\Models\Event::class, function (Faker\Generator $faker) {
    $name = $faker->sentence;
    return [
        'name' => $name,
        'slug' => str_slug($name),
        'date' => $faker->dateTimeBetween('-10 years'),
        'venue_id' => function () {
            return factory(App\Models\Venue::class)->create()->id;
        },
    ];
});

$factory->define(App\Models\Champion::class, function (Faker\Generator $faker) {
    return [
        'wrestler_id' => function () {
            return factory(App\Models\Wrestler::class)->create()->id;
        },
        'title_id' => function () {
            return factory(App\Models\Title::class)->create()->id;
        },
        'won_on' => $faker->date(),
    ];
});

$factory->define(App\Models\MatchType::class, function (Faker\Generator $faker) {
    $name = $faker->sentence;
    return [
        'name' => $name,
        'slug' => str_slug($name),
        'number_of_competitors' => 2
    ];
});

$factory->define(App\Models\Stipulation::class, function (Faker\Generator $faker) {
    $name = $faker->sentence;
    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});

$factory->define(App\Models\MatchDecision::class, function (Faker\Generator $faker) {
    $name = $faker->sentence;
    return [
        'name' => $name,
        'slug' => str_slug($name),
    ];
});

$factory->define(App\Models\Venue::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(3),
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'postcode' => substr($faker->postcode, 0, 5)
    ];
});

$factory->define(App\Models\Referee::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'hired_at' => Carbon::parse('-2 weeks')
    ];
});
