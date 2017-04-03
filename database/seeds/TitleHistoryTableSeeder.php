<?php

use App\TitleHistory;
use Illuminate\Database\Seeder;

class TitleHistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oldChampion = Wrestler::get()->random(1);
        Title::all()->each(function($title) {
            $title->champions()->newChampion($oldChampion->id, $newChampion->id);
        });
    }
}
