<?php

use App\Wrestler;
use App\WrestlerRetirement;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WrestlersRetirementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Wrestler::active()->get()->random(10)->each(function($item) {
//            $item->retire()->unretire();
//        });

//        Wrestler::active()->get()->random(4)->each(function($item) {
//            $item->retire();
//        });

        Wrestler::retired()->each(function($wrestler) {
            dd($wrestler->injuries->last()->healed_at);
            $lastInjuredHealedDate = (new Carbon)->parse($wrestler->hired_at);
            WrestlerRetirement::create(['wrestler_id' => $wrestler->id, 'retired_at' => Carbon::now()->subMonths(3)]);
        });
    }
}
