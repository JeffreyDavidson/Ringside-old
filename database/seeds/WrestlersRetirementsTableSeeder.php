<?php

use Carbon\Carbon;
use App\Models\Wrestler;
use App\Models\Retirement;
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

        Wrestler::retired()->each(function ($wrestler) {
            Retirement::create(['wrestler_id' => $wrestler->id, 'retired_at' => Carbon::now()->subMonths(3)]);
        });
    }

    private function getRetiredAtDate($wrestler)
    {
        return $wrestler->hired_at->addYear()->addDays(
            rand(1, Carbon::now()->subMonths(6)->diffInDays($wrestler->hired_at->addYear()))
        );
    }

    private function getEndedAtDate($injured_at)
    {
        return $injured_at->copy()->addDays(rand(1, 365));
    }
}
