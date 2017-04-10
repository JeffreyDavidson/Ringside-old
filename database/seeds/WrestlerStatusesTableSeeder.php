<?php

use App\Status;
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
        factory(Status::class)->create(['name' => 'Active']);
        factory(Status::class)->create(['name' => 'Inactive']);
        factory(Status::class)->create(['name' => 'Injured']);
        factory(Status::class)->create(['name' => 'Suspended']);
        factory(Status::class)->create(['name' => 'Retired']);
    }
}
