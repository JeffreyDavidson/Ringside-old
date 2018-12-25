<?php

use Carbon\Carbon;
use App\Models\Roster\Referee;
use Illuminate\Database\Seeder;

class RefereesTableSeeder extends Seeder
{
    private $refereeCount = 1;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startingDate = Carbon::parse('First Monday of January 2010');
        $now = Carbon::now();

        // Create 10 initial referees.
        for ($this->refereeCount; $this->refereeCount <= 10; $this->refereeCount++) {
            factory(Referee::class)->create([
                'hired_at' => $startingDate->hour(mt_rand(9, 18)),
            ]);
        }

        // Create 5 referees every 3 years.
        while ($startingDate->addYears(3)->lte($now)) {
            for ($x = 1; $x <= 5; $x++) {
                factory(Referee::class)->create([
                    'hired_at' => $startingDate->hour(mt_rand(9, 18)),
                ]);
                $this->refereeCount++;
            }
        }
    }
}
