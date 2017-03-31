<?php

use App\Wrestler;
use App\WrestlerRetire;
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
        Wrestler::active()->get()->random(10)->each(function($item) {
            $item->retire()->unretire();
        });

        Wrestler::active()->get()->random(4)->each(function($item) {
            $item->retire();
        });
    }
}
