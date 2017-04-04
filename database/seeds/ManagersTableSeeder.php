<?php

use App\Manager;
use Illuminate\Database\Seeder;

class ManagersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 100; $i++)
        {
            factory(Manager::class)->create(['name' => 'Manager '.$i]);
        }
    }
}
