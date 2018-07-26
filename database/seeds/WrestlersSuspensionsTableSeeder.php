<?php

use Carbon\Carbon;
use App\Models\Wrestler;
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
        Wrestler::hasStatus('Suspended')->each(function ($wrestler) {
            Suspension::create(['wrestler_id' => $wrestler->id, 'retired_at' => Carbon::now()->subMonths(3)]);
        });
    }
}
