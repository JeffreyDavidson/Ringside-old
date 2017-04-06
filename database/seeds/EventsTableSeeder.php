<?php

use App\Event;
use Carbon\Carbon;
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
                $event->matches()->save(factory(App\Match::class)->create([
                    'match_number'  => $j,
                    'match_type_id' => 1,
                ]));
            }
        }
    }
}