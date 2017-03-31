<?php

use App\Wrestler;
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
        Wrestler::active()->get()->random(50)->each(function($item) {
        	$item->injure()->heal();
        });

        Wrestler::active()->get()->random(4)->each(function($item) {
            $item->injure();
        });
    }
}
