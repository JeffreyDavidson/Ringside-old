<?php

use App\MatchStipulation;
use Illuminate\Database\Seeder;

class MatchStipulationsTableSeeder extends Seeder {

    public function run()
    {
        factory(MatchStipulation::class)->create(['name' => 'Ladder Match', 'slug' => 'ladder']);
        factory(MatchStipulation::class)->create(['name' => 'Cage Match', 'slug' => 'cage']);
        factory(MatchStipulation::class)->create(['name' => 'Flag Match', 'slug' => 'flag']);
        factory(MatchStipulation::class)->create(['name' => 'Casket Match', 'slug' => 'casket']);
        factory(MatchStipulation::class)->create(['name' => 'First Blood Match', 'slug' => 'firstblood']);
        factory(MatchStipulation::class)->create(['name' => 'Leather Strap Match', 'slug' => 'leatherstrap']);
        factory(MatchStipulation::class)->create(['name' => 'Hell in a Cell Match', 'slug' => 'hellinacell']);
        factory(MatchStipulation::class)->create(['name' => 'Elimination Chamber Match', 'slug' => 'eliminationchamber']);
        factory(MatchStipulation::class)->create(['name' => 'Two Out Of Three Falls Match', 'slug' => 'twooutofthree']);
        factory(MatchStipulation::class)->create(['name' => 'Pinfalls Count Anywhere Match', 'slug' => 'pinfallsanywhere']);
        factory(MatchStipulation::class)->create(['name' => 'I Quit Match', 'slug' => 'iquit']);
        factory(MatchStipulation::class)->create(['name' => 'Submission Only Match', 'slug' => 'submission']);
        factory(MatchStipulation::class)->create(['name' => 'Monster\'s Ball Match', 'slug' => 'monstersball']);
        factory(MatchStipulation::class)->create(['name' => 'Ultimate X Match', 'slug' => 'ultimatex']);
        factory(MatchStipulation::class)->create(['name' => 'No Disqualification Match', 'slug' => 'nodq']);
        factory(MatchStipulation::class)->create(['name' => 'Elimination Match', 'slug' => 'elimination']);
        factory(MatchStipulation::class)->create(['name' => 'Title For Title Match', 'slug' => 'titlefortitle']);
        factory(MatchStipulation::class)->create(['name' => 'Empty Arena Match', 'slug' => 'emptyarena']);
        factory(MatchStipulation::class)->create(['name' => 'Lumberjack Match', 'slug' => 'lumberjack']);
        factory(MatchStipulation::class)->create(['name' => 'Bra and Panties Match', 'slug' => 'brapanties']);
        factory(MatchStipulation::class)->create(['name' => 'Tuxedo Match', 'slug' => 'tuxedo']);
        factory(MatchStipulation::class)->create(['name' => 'Evening Gown Match', 'slug' => 'eveninggown']);
        factory(MatchStipulation::class)->create(['name' => 'Arm Wrestling Match', 'slug' => 'armwrestling']);
        factory(MatchStipulation::class)->create(['name' => 'Pillow Fight Match', 'slug' => 'pillowfight']);
        factory(MatchStipulation::class)->create(['name' => 'Last Man Standing Match', 'slug' => 'lastmanstanding']);
        factory(MatchStipulation::class)->create(['name' => 'No Count Out Match', 'slug' => 'nocountout']);
        factory(MatchStipulation::class)->create(['name' => 'Loser Leaves Town Match', 'slug' => 'loserleaves']);
        factory(MatchStipulation::class)->create(['name' => 'Kiss My Foot Match', 'slug' => 'kissmyfoot']);
        factory(MatchStipulation::class)->create(['name' => 'Boiler Room Brawl Match', 'slug' => 'boilerroom']);
        factory(MatchStipulation::class)->create(['name' => 'Parking Lot Brawl Match', 'slug' => 'parkinglot']);
        factory(MatchStipulation::class)->create(['name' => 'Tables Match', 'slug' => 'tables']);
        factory(MatchStipulation::class)->create(['name' => 'Chairs Match', 'slug' => 'chairs']);
        factory(MatchStipulation::class)->create(['name' => 'Tables, Ladders and Chairs Match', 'slug' => 'tlc']);
        factory(MatchStipulation::class)->create(['name' => 'Object On A Pole Match', 'slug' => 'objectonpole']);
        factory(MatchStipulation::class)->create(['name' => 'Inferno Match', 'slug' => 'inferno']);
        factory(MatchStipulation::class)->create(['name' => 'Buried Alive Match', 'slug' => 'buriedalive']);
        factory(MatchStipulation::class)->create(['name' => 'Beat The Clock Match', 'slug' => 'beatclock']);
        factory(MatchStipulation::class)->create(['name' => 'Tournament Match', 'slug' => 'tournament']);
    }
}
