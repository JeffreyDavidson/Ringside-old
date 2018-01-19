<?php

use App\Models\MatchDecision;
use Illuminate\Database\Seeder;

class MatchDecisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MatchDecision::class)->create(['name' => 'Pinfall',  'slug' => 'pinfall']);
        factory(MatchDecision::class)->create(['name' => 'Submission',  'slug' => 'submission']);
        factory(MatchDecision::class)->create(['name' => 'Disqualification',  'slug' => 'dq']);
        factory(MatchDecision::class)->create(['name' => 'Countout',  'slug' => 'countout']);
        factory(MatchDecision::class)->create(['name' => 'Knockout',  'slug' => 'knockout']);
        factory(MatchDecision::class)->create(['name' => 'Stipulation',  'slug' => 'stipulation']);
        factory(MatchDecision::class)->create(['name' => 'Forfeit',  'slug' => 'forfeit']);
        factory(MatchDecision::class)->create(['name' => 'Time Limit Draw',  'slug' => 'draw']);
        factory(MatchDecision::class)->create(['name' => 'No Decision',  'slug' => 'nodecision']);
        factory(MatchDecision::class)->create(['name' => 'Reversed Decision',  'slug' => 'revdecision']);
    }
}
