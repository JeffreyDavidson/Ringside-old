<?php

use App\Models\MatchType;
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
        factory(MatchType::class)->create(['name' => 'Singles Match',  'slug' => 'singles', 'number_of_sides' => 2, 'total_competitors' => 2]);
        factory(MatchType::class)->create(['name' => 'Tag Team Match', 'slug' => 'tagteam', 'number_of_sides' => 2, 'total_competitors' => 4]);
        factory(MatchType::class)->create(['name' => 'Triple Threat Match', 'slug' => 'triplethreat', 'number_of_sides' => 3, 'total_competitors' => 3]);
        factory(MatchType::class)->create(['name' => 'Triangle Match', 'slug' => 'triangle', 'number_of_sides' => 3, 'total_competitors' => 3]);
        factory(MatchType::class)->create(['name' => 'Fatal 4 Way Match', 'slug' => 'fatal4way', 'number_of_sides' => 4, 'total_competitors' => 4]);
        factory(MatchType::class)->create(['name' => 'Six Man Tag Team Match', 'slug' => '6man', 'number_of_sides' => 2, 'total_competitors' => 6]);
        factory(MatchType::class)->create(['name' => 'Eight Man Tag Team Match', 'slug' => '8man', 'number_of_sides' => 2, 'total_competitors' => 8]);
        factory(MatchType::class)->create(['name' => 'Ten Man Tag Team Match', 'slug' => '10man', 'number_of_sides' => 2, 'total_competitors' => 20]);
        factory(MatchType::class)->create(['name' => 'Two On One Handicap Match', 'slug' => '21handicap', 'number_of_sides' => 2, 'total_competitors' => 3]);
        factory(MatchType::class)->create(['name' => 'Three On Two Handicap Match', 'slug' => '32handicap', 'number_of_sides' => 2, 'total_competitors' => 5]);
        factory(MatchType::class)->create(['name' => 'Battle Royal Match', 'slug' => 'battleroyal', 'number_of_sides' => NULL, 'total_competitors' => NULL]);
        factory(MatchType::class)->create(['name' => 'Royal Rumble Match', 'slug' => 'royalrumble', 'number_of_sides' => NULL, 'total_competitors' => NULL]);
        factory(MatchType::class)->create(['name' => 'Tornado Tag Team Match', 'slug' => 'tornadotag', 'number_of_sides' => 2, 'total_competitors' => 4]);
        factory(MatchType::class)->create(['name' => 'Gauntlet Match', 'slug' => 'gauntlet', 'number_of_sides' => 2, 'total_competitors' => NULL]);
    }
}
