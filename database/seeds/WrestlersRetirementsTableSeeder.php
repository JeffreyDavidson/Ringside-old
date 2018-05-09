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
        Wrestler::hasStatus('Retired')->each(function ($wrestler) {
            Retirement::create(['wrestler_id' => $wrestler->id, 'retired_at' => Carbon::now()->subMonths(3)]);
        });
    }
}
