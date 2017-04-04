<?php

use App\Event;
use App\Match;
use Illuminate\Database\Seeder;

class MatchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::all()->each(function($event) {
            for($i = 1; $i<= 8; $i++) {
                Match::create([
                    'event_id' => $event->id,
                    'match_number' => $i,
                    'match_type_id' => 1,
                    'preview' => 'Maecenas sed diam eget risus varius blandit sit amet non magna. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Aenean lacinia bibendum nulla sed consectetur. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Nulla vitae elit libero, a pharetra augue.'
                ]);
            }
        });
    }
}