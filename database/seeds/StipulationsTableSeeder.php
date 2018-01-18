<?php

use App\Models\Stipulation;
use Illuminate\Database\Seeder;

class StipulationsTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            factory(Stipulation::class)->create(['name' => 'Stipulation '.$i, 'slug' => 'stipulation'.$i]);
        }
    }
}
