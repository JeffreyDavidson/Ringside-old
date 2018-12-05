<?php

use App\Models\Roster\Wrestler;
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
        Wrestler::hasStatus('retired')->each(function ($wrestler) {
            $wrestler->retire();
        });
    }
}
