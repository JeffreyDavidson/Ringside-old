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
        for($i = 1; $i <= 10; $i++) {
            factory(Title::class)->create(['name' => 'Title '.$i, 'slug' => 'title'.$i]);
        }
    }
}
