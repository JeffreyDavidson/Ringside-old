<?php

use App\Title;
use Illuminate\Database\Seeder;

class TitlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i <= 5; $i++) {
            factory(Title::class)->create(['name' => 'Title '.$i]);
        }
    }
}
