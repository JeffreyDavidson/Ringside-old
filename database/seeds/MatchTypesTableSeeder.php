<?php

use App\MatchType;
use Illuminate\Database\Seeder;

class MatchTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MatchType::class)->create(['name' => 'Singles Match',  'slug' => 'singles']);
        factory(MatchType::class)->create(['name' => 'Tag Team Match', 'slug' => 'tagteam']);
        factory(MatchType::class)->create(['name' => 'Triple Threat Match', 'slug' => 'triplethreat']);
        factory(MatchType::class)->create(['name' => 'Triangle Match', 'slug' => 'triangle']);
        factory(MatchType::class)->create(['name' => 'Fatal 4 Way Match', 'slug' => 'fatal4way']);
        factory(MatchType::class)->create(['name' => 'Six Man Tag Team Match', 'slug' => '6man']);
        factory(MatchType::class)->create(['name' => 'Eight Man Tag Team Match', 'slug' => '8man']);
        factory(MatchType::class)->create(['name' => 'Ten Man Tag Team Match', 'slug' => '10man']);
        factory(MatchType::class)->create(['name' => 'Two On One Handicap Match', 'slug' => '21handicap']);
        factory(MatchType::class)->create(['name' => 'Three On Two Handicap Match', 'slug' => '32handicap']);
        factory(MatchType::class)->create(['name' => 'Battle Royal Match', 'slug' => 'battleroyal']);
        factory(MatchType::class)->create(['name' => 'Royal Rumble Match', 'slug' => 'royalrumble']);
        factory(MatchType::class)->create(['name' => 'Tornado Tag Team Match', 'slug' => 'tornadotag']);
        factory(MatchType::class)->create(['name' => 'Gauntlet Match', 'slug' => 'gauntlet']);
    }
}
