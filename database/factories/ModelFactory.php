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

$factory->define(App\Models\Injury::class, function (Faker\Generator $faker) {
    return [
        'wrestler_id' => factory(App\Models\Wrestler::class)->lazy(),
        'injured_at' => Carbon::now('-2 years'),
        'healed_at' => Carbon::now('-2 days'),
    ];
});

$factory->define(App\Models\Retirement::class, function (Faker\Generator $faker) {
    return [
        'retiree_id' => $faker->randomDigitNotNull,
        'retiree_type' => $faker->sentence,
        'retired_at' => Carbon::now('-2 years'),
        'ended_at' => Carbon::now('-2 days'),
    ];
});

$factory->define(App\Models\Manager::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});

$factory->define(App\Models\Title::class, function (Faker\Generator $faker) {
    $name = $faker->sentence;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'is_active' => true,
        'introduced_at' => Carbon::now()->subMonths(8),
    ];
});

$factory->state(App\Models\Title::class, 'active', function ($faker) {
    return ['is_active' => true];
});

$factory->state(App\Models\Title::class, 'inactive', function ($faker) {
    return ['is_active' => false];
});

$factory->define(App\Models\Match::class, function (Faker\Generator $faker) {
    return [
        'event_id' => factory(App\Models\Event::class)->lazy(),
        'match_type_id' => factory(App\Models\MatchType::class)->lazy(),
        'stipulation_id' => factory(App\Models\Stipulation::class)->lazy(),
        'preview' => $faker->paragraphs(3, true),
        'result' => $faker->paragraphs(3, true),
    ];
});

$factory->state(App\Models\Match::class, 'retired', function ($faker) {
    return [
        'retired_at' => function (array $title) use ($faker) {
            return $faker->dateTimeBetween($title['introduced_at']);
        },
    ];
});

$factory->define(App\Models\Event::class, function (Faker\Generator $faker) {
    $name = $faker->sentence;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'date' => $faker->dateTimeBetween('-10 years'),
        'venue_id' => factory(App\Models\Venue::class)->lazy(),
    ];
});

$factory->define(App\Models\Championship::class, function (Faker\Generator $faker) {
    return [
        'wrestler_id' => factory(App\Models\Wrestler::class)->lazy(),
        'title_id' => factory(App\Models\Title::class)->lazy(),
        'won_on' => Carbon::now(),
    ];
});

$factory->define(App\Models\MatchType::class, function (Faker\Generator $faker) {
    $name = $faker->sentence;

    return [
        'name' => $name,
        'slug' => str_slug($name),
        'number_of_sides' => 2,
        'total_competitors' => 2,
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
        'postcode' => substr($faker->postcode, 0, 5),
    ];
});

$factory->define(App\Models\Referee::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'hired_at' => Carbon::parse('-2 weeks'),
    ];
});

$factory->define(App\Models\Suspension::class, function (Faker\Generator $faker) {
    return [
        'wrestler_id' => factory(App\Models\Wrestler::class)->lazy(),
        'suspended_at' => Carbon::now('-2 years'),
        'ended_at' => Carbon::now('-2 days'),
    ];
});
