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
        Wrestler::hasStatus('retired')->each(function ($wrestler) {
            $wrestler->retire();
        });
    }
}
