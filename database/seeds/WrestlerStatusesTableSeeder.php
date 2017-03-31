<?php

use App\WrestlerStatus;
use Illuminate\Database\Seeder;

class WrestlerStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(WrestlerStatus::class)->create(['name' => 'Active']);
        factory(WrestlerStatus::class)->create(['name' => 'Inactive']);
        factory(WrestlerStatus::class)->create(['name' => 'Injured']);
        factory(WrestlerStatus::class)->create(['name' => 'Suspended']);
        factory(WrestlerStatus::class)->create(['name' => 'Retired']);
    }
}
