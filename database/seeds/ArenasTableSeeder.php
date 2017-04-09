<?php

use App\Arena;
use Illuminate\Database\Seeder;

class ArenasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 100; $i++) {
            factory(Arena::class)->create(['name' => 'Arena '.$i]);
        }
    }
}
