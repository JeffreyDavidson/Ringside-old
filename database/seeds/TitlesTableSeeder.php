<?php

use App\Title;
use Carbon\Carbon;
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
		factory(Title::class)->create(['name' => 'Title 1', 'slug' => 'title1', 'introduced_at' => Carbon::parse('January 1, 1970')]);

        for($i = 2; $i <= 10; $i++) {
            factory(Title::class)->create(['name' => 'Title '.$i, 'slug' => 'title'.$i]);
        }
    }
}
