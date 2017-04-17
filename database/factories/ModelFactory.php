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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Wrestler::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
        'slug' => $faker->name,
        'status_id' => $faker->numberBetween(1, 5),
        'hired_at' => $faker->dateTimeBetween('-30 years','-1 year')
    ];
});

$factory->define(App\WrestlerStatus::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->sentence(3),
    ];
});

$factory->define(App\WrestlerInjury::class, function (Faker\Generator $faker) {

    return [
        'wrestler_id' => function () {
            return factory(App\Wrestler::class)->create()->id;
        },
        'injured_at' => Carbon::now('-2 years'),
        'healed_at' => Carbon::now('-2 days'),
    ];
});

$factory->define(App\WrestlerRetirement::class, function (Faker\Generator $faker) {

    return [
        'wrestler_id' => function () {
            return factory(App\Wrestler::class)->create()->id;
        },
        'retired_at' => Carbon::now('-2 years'),
        'ended_at' => Carbon::now('-2 days'),
    ];
});

$factory->define(App\WrestlerBio::class, function (Faker\Generator $faker) {

    return [
        'wrestler_id' => function () {
            return factory(App\Wrestler::class)->create()->id;
        },
        'hometown' => $faker->city . ', ' . $faker->state,
        'height' => $faker->numberBetween(63, 84),
        'weight' => $faker->numberBetween(175, 400),
        'signature_move' => $faker->unique()->sentence(3)
    ];
});

$factory->state(App\Wrestler::class, 'active', function ($faker) {
    return [
        'status_id' => 1,
    ];
});

$factory->state(App\Wrestler::class, 'inactive', function ($faker) {
    return [
        'status_id' => 2,
    ];
});

$factory->state(App\Wrestler::class, 'injured', function ($faker) {
    return [
        'status_id' => 3,
    ];
});

$factory->state(App\Wrestler::class, 'suspended', function ($faker) {
    return [
        'status_id' => 4,
    ];
});

$factory->state(App\Wrestler::class, 'retired', function ($faker) {
    return [
        'status_id' => 5,
    ];
});

$factory->define(App\Manager::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->name,
    ];
});

$factory->define(App\Title::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->sentence(3),
        'slug' => $faker->sentence(3),
        'introduced_at' => $faker->date(),
        'retired_at' => function (array $title) use ($faker) {
            return $faker->boolean(80) ? $faker->dateTimeBetween($title['introduced_at']) : null;
        }
    ];
});

$factory->define(App\Match::class, function (Faker\Generator $faker) {

    return [
        'match_type_id' => function () {
            return factory(App\MatchType::class)->create()->id;
        },
        'match_number' => $faker->randomNumber(),
        'preview' => $faker->paragraphs(3, true),
		'title_match' => $faker->boolean(5)
    ];
});

$factory->define(App\Event::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->sentence(3),
        'slug' => $faker->sentence(3),
        'date' => $faker->dateTimeBetween('-10 years'),
        'arena_id' => function () {
            return factory(App\Arena::class)->create()->id;
        },
    ];
});

$factory->define(App\TitleHistory::class, function (Faker\Generator $faker) {

    return [
        'wrestler_id' => function () {
            return factory(App\Wrestler::class)->create()->id;
        },
        'title_id' => function () {
            return factory(App\Title::class)->create()->id;
        },
        'won_on' => $faker->date(),
    ];
});

$factory->define(App\MatchType::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->sentence(3),
        'slug' => $faker->sentence(3),
    ];
});

$factory->define(App\Stipulation::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->sentence(3),
        'slug' => $faker->sentence(3),
    ];
});

$factory->define(App\MatchDecision::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->sentence(3),
        'slug' => $faker->sentence(3),
    ];
});

$factory->define(App\Arena::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->sentence(3),
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'postcode' => substr($faker->postcode, 0, 5)
    ];
});


$factory->define(App\Referee::class, function (Faker\Generator $faker) {

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});


