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
            Event::create(['name' => 'Event '.$i, 'slug' => 'event'.$i, 'date' => Carbon::parse('-10 years')]);
        }
    }
}