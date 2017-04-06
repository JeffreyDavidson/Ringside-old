<?php

use App\Event;
use App\Stipulation;
use App\Title;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 50; $i++) {
            $event = factory(Event::class)->create(['name' => 'Event '.$i, 'slug' => 'event'.$i]);

            for($j = 1; $j <= 8; $j++) {
                $event->matches()->save($match = factory(App\Match::class)->create([
                    'match_number'  => $j,
                    'match_type_id' => 1,
                ]));

                if($this->chance(10)) {
                    $match->addTitles(Title::introducedBefore($event->date)->random()->first()->get());
                }

                if($this->chance(10)) {
                    $match->addStipulations(Stipulation::all());
                }
            }
        }
    }

    public function chance(int $percent) {
        return rand(0,100) < $percent;
    }
}