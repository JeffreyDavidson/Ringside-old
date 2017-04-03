<?php

use App\Wrestler;
use App\WrestlerInjury;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WrestlersInjuriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Wrestler::all()->random(100)->each(function($wrestler) {
            $hiredAt = (new Carbon)->parse($wrestler->hired_at);
            $hiredAtPlusAYear = $hiredAt->addYear(1);
            $sixMonthsAgo = Carbon::now()->subMonths(6);

            $diffInDays = $sixMonthsAgo->diffInDays($hiredAtPlusAYear);

            $daysBetweenHiredAtAndInjuredAt = rand(1, $diffInDays);

            $injuredAtDate = $hiredAtPlusAYear->addDays($daysBetweenHiredAtAndInjuredAt);

            $daysBetweenInjuredAtAndHealedAt = rand(1, 365);
            $healedAtDate = $injuredAtDate->copy()->addDays($daysBetweenInjuredAtAndHealedAt);

            WrestlerInjury::create([
                'wrestler_id' => $wrestler->id,
                'injured_at' => $injuredAtDate,
                'healed_at' => $healedAtDate
            ]);
        });

        Wrestler::injured()->each(function($wrestler) {
            WrestlerInjury::create(['wrestler_id' => $wrestler->id, 'injured_at' => Carbon::now()->subMonths(3)]);
        });
    }
}
