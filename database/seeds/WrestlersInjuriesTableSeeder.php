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
        Wrestler::all()->random(50)->each(function($wrestler) {
            $startDate = Carbon::now();
            $endDate   = Carbon::now()->subDays(7);

            $injury = WrestlerInjury::create([
                'wrestler_id' => $wrestler->id,
//                'injured_at' => Carbon::createFromTimestamp(rand(Carbon::parse($wrestler->hired_at), Carbon::now()->subDays(7)))
                'injured_at' => Carbon::parse()->between(Carbon::parse($wrestler->hired_at), Carbon::now()->subMonths(6))
            ]);

//            $injury = WrestlerInjury::create(['wrestler_id' => $wrestler->id, 'injured_at' => Carbon::parse()->between(Carbon::parse($wrestler->hired_at), Carbon::now())->subMonths(6)]);
//            WrestlerInjury::where(['wrestler_id' => $injury->wrestler_id])->whereNull('healed_at')->update(['healed_at' => Carbon::parse($injury->injured_at)->addMonths(4)]);
        });

        Wrestler::injured()->each(function($wrestler) {
            WrestlerInjury::create(['wrestler_id' => $wrestler->id, 'injured_at' => Carbon::now()->subMonths(3)]);
        });
    }
}
