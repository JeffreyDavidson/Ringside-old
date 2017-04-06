<?php

use App\Stipulation;
use Illuminate\Database\Seeder;

class StipulationsTableSeeder extends Seeder {

    public function run()
    {
        factory(Stipulation::class)->create(['name' => 'Ladder ', 'slug' => 'ladder']);
        factory(Stipulation::class)->create(['name' => 'Cage ', 'slug' => 'cage']);
        factory(Stipulation::class)->create(['name' => 'Flag ', 'slug' => 'flag']);
        factory(Stipulation::class)->create(['name' => 'Casket ', 'slug' => 'casket']);
        factory(Stipulation::class)->create(['name' => 'First Blood ', 'slug' => 'firstblood']);
        factory(Stipulation::class)->create(['name' => 'Leather Strap ', 'slug' => 'leatherstrap']);
        factory(Stipulation::class)->create(['name' => 'Hell in a Cell ', 'slug' => 'hellinacell']);
        factory(Stipulation::class)->create(['name' => 'Elimination Chamber ', 'slug' => 'eliminationchamber']);
        factory(Stipulation::class)->create(['name' => 'Two Out Of Three Falls ', 'slug' => 'twooutofthree']);
        factory(Stipulation::class)->create(['name' => 'Pinfalls Count Anywhere ', 'slug' => 'pinfallsanywhere']);
        factory(Stipulation::class)->create(['name' => 'I Quit ', 'slug' => 'iquit']);
        factory(Stipulation::class)->create(['name' => 'Submission Only ', 'slug' => 'submission']);
        factory(Stipulation::class)->create(['name' => 'Monster\'s Ball ', 'slug' => 'monstersball']);
        factory(Stipulation::class)->create(['name' => 'Ultimate X ', 'slug' => 'ultimatex']);
        factory(Stipulation::class)->create(['name' => 'No Disqualification ', 'slug' => 'nodq']);
        factory(Stipulation::class)->create(['name' => 'Elimination ', 'slug' => 'elimination']);
        factory(Stipulation::class)->create(['name' => 'Title For Title ', 'slug' => 'titlefortitle']);
        factory(Stipulation::class)->create(['name' => 'Empty Arena ', 'slug' => 'emptyarena']);
        factory(Stipulation::class)->create(['name' => 'Lumberjack ', 'slug' => 'lumberjack']);
        factory(Stipulation::class)->create(['name' => 'Bra and Panties ', 'slug' => 'brapanties']);
        factory(Stipulation::class)->create(['name' => 'Tuxedo ', 'slug' => 'tuxedo']);
        factory(Stipulation::class)->create(['name' => 'Evening Gown ', 'slug' => 'eveninggown']);
        factory(Stipulation::class)->create(['name' => 'Arm Wrestling ', 'slug' => 'armwrestling']);
        factory(Stipulation::class)->create(['name' => 'Pillow Fight ', 'slug' => 'pillowfight']);
        factory(Stipulation::class)->create(['name' => 'Last Man Standing ', 'slug' => 'lastmanstanding']);
        factory(Stipulation::class)->create(['name' => 'No Count Out ', 'slug' => 'nocountout']);
        factory(Stipulation::class)->create(['name' => 'Loser Leaves Town ', 'slug' => 'loserleaves']);
        factory(Stipulation::class)->create(['name' => 'Kiss My Foot ', 'slug' => 'kissmyfoot']);
        factory(Stipulation::class)->create(['name' => 'Boiler Room Brawl ', 'slug' => 'boilerroom']);
        factory(Stipulation::class)->create(['name' => 'Parking Lot Brawl ', 'slug' => 'parkinglot']);
        factory(Stipulation::class)->create(['name' => 'Tables ', 'slug' => 'tables']);
        factory(Stipulation::class)->create(['name' => 'Chairs ', 'slug' => 'chairs']);
        factory(Stipulation::class)->create(['name' => 'Tables, Ladders and Chairs ', 'slug' => 'tlc']);
        factory(Stipulation::class)->create(['name' => 'Object On A Pole ', 'slug' => 'objectonpole']);
        factory(Stipulation::class)->create(['name' => 'Inferno ', 'slug' => 'inferno']);
        factory(Stipulation::class)->create(['name' => 'Buried Alive ', 'slug' => 'buriedalive']);
        factory(Stipulation::class)->create(['name' => 'Beat The Clock ', 'slug' => 'beatclock']);
        factory(Stipulation::class)->create(['name' => 'Tournament ', 'slug' => 'tournament']);
    }
}
