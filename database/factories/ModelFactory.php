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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'role_id' => $faker->numberBetween(1, 4),
        'remember_token' => str_random(10),
    ];
});

$factory->state(App\Models\User::class, 'basic', function ($faker) {
    return [
        'role_id' => 1,
    ];
});

$factory->state(App\Models\User::class, 'editor', function ($faker) {
    return [
        'role_id' => 2,
    ];
});

$factory->state(App\Models\User::class, 'admin', function ($faker) {
    return [
        'role_id' => 3,
    ];
});

$factory->state(App\Models\User::class, 'super-admin', function ($faker) {
    return [
        'role_id' => 4,
    ];
});

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

$factory->define(App\Models\Champion::class, function (Faker\Generator $faker) {
    return [
        'title_id' => $faker->word,
        'wrestler_id' => $faker->word,
        'won_on' => Carbon::now()
    ];
});

$factory->define(App\Models\Wrestler::class, function (Faker\Generator $faker) {
    $name = $faker->sentence;
    return [
        'name' => $name,
        'slug' => str_slug($name),
        'status_id' => $faker->numberBetween(1, 5),
        'hometown' => $faker->city . ', ' . $faker->state,
        'height' => $faker->numberBetween(63, 84),
        'weight' => $faker->numberBetween(175, 400),
        'signature_move' => $faker->unique()->sentence(3),
        'hired_at' => $faker->dateTimeBetween('-30 years','-1 year'),
    ];
});

$factory->state(App\Models\Wrestler::class, 'active', function ($faker) {
    return ['status_id' => WrestlerStatus::SUSPENDED];
});

$factory->state(App\Models\Wrestler::class, 'inactive', function ($faker) {
    return ['status_id' => WrestlerStatus::INACTIVE];
});

$factory->state(App\Models\Wrestler::class, 'injured', function ($faker) {
    return ['status_id' => WrestlerStatus::INJURED];
});

$factory->state(App\Models\Wrestler::class, 'suspended', function ($faker) {
    return ['status_id' => WrestlerStatus::SUSPENDED];
});

$factory->state(App\Models\Wrestler::class, 'retired', function ($faker) {
    return ['status_id' => WrestlerStatus::RETIRED];
});

$factory->define(App\Models\WrestlerStatus::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->sentence(3),
    ];
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
        'name' => $faker->name,
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
        'event_id' => function () {
            return factory(App\Models\Event::class)->create()->id;
        },
        'match_number' => $faker->randomNumber(),
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
    ];
});


