<?php

use App\Models\Wrestler;
use App\Models\Injury;
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
            factory(Injury::class)->create([
                'wrestler_id' => $wrestler->id,
                'healed_at' => $this->getHealedAtDate($injured_at = $this->getInjuredAtDate($wrestler)),
                'injured_at' => $injured_at
            ]);
        });

        Wrestler::injured()->each(function($wrestler) {
            factory(Injury::class)->create([
                'wrestler_id' => $wrestler->id,
                'injured_at' => Carbon::now()->subMonths(3)
            ]);
        });
    }

    private function getInjuredAtDate($wrestler){
        return $wrestler->hired_at->addYear()->addDays(
            rand(1, Carbon::now()->subMonths(6)->diffInDays($wrestler->hired_at->addYear()))
        );
    }

    private function getHealedAtDate($injured_at) {
        return $injured_at->copy()->addDays(rand(1, Carbon::parse("-4 months")->diffInDays($injured_at) > 365 ? 365 : Carbon::parse("-4 months")->diffInDays($injured_at)));
    }
}
