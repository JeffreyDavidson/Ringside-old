<?php

use App\Models\TitleHistory;
use App\Models\Title;
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
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::parse("-10 days"), 'lost_on' => Carbon::parse("-8 days")]);
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::parse("-20 days"), 'lost_on' => Carbon::parse("-18 days")]);
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::parse("-30 days"), 'lost_on' => Carbon::parse("-28 days")]);
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::parse("-40 days"), 'lost_on' => Carbon::parse("-38 days")]);
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::parse("-50 days"), 'lost_on' => Carbon::parse("-48 days")]);
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::parse("-60 days"), 'lost_on' => Carbon::parse("-58 days")]);
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::parse("-70 days"), 'lost_on' => Carbon::parse("-68 days")]);
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::parse("-80 days"), 'lost_on' => Carbon::parse("-78 days")]);
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::parse("-90 days"), 'lost_on' => Carbon::parse("-88 days")]);
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::parse("-100 days"), 'lost_on' => Carbon::parse("-98 days")]);
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => $wid = \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::parse("-110 days"), 'lost_on' => Carbon::parse("-108 days")]);
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => $wid, 'won_on' => Carbon::parse("-120 days"), 'lost_on' => Carbon::parse("-118 days")]);
            TitleHistory::create(['title_id' => $title->id, 'wrestler_id' => \App\Wrestler::inRandomOrder()->first()->id, 'won_on' => Carbon::now()]);
        });
    }
}
