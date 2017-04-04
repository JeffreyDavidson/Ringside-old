<?php

use App\TitleHistory;
use App\Title;
use Carbon\Carbon;
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
        Title::all()->each(function($title) {
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::now()]);
        });
    }
}
