<?php

use App\Models\Wrestler;
use App\Models\Injury;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WrestlersSuspensionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $injuries = Wrestler::all()->random()->each(function($wrestler) {
            $injuredAt = $this->getInjuredAtDate($wrestler);
            $healedAt = $this->getHealedAtDate($injuredAt);
            $wrestler->injure($injuredAt);
            $wrestler->heal($healedAt);
        });

        dd($injuries);
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
