<?php

use App\Stipulation;
use Illuminate\Database\Seeder;

class StipulationsTableSeeder extends Seeder {

    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            factory(Stipulation::class)->create(['name' => 'Stipulation '. $i, 'slug' => 'stipulation'. $i]);
        }
    }
}
