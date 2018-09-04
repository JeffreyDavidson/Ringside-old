<?php

use Faker\Generator as Faker;
use Carbon\Carbon;
use App\Models\RosterMember;

$factory->define(RosterMember::class, function (Faker $faker) {
    return [
        'roster_member_id' => $faker->randomNumber(),
        'roster_member_type' => $faker->sentence(),
        'is_active' => true,
        'hired_at' => $faker->dateTimeBetween('-30 years', '-1 year'),
    ];
});

$factory->state(RosterMember::class, 'active', ['is_active' => true, 'hired_at' => Carbon::today()]);
$factory->state(RosterMember::class, 'inactive', ['is_active' => false, 'hired_at' => Carbon::tomorrow()]);

