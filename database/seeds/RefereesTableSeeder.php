<?php

use Carbon\Carbon;
use App\Models\Referee;
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
        $startingDate = Carbon::parse('First Monday of January 2000');
        $now = Carbon::now();

        for ($this->refereeCount; $this->refereeCount <= 10; $this->refereeCount++) {
            factory(Referee::class)->create([
                'hired_at' => $startingDate,
            ]);
        }

        while ($startingDate->addYears(3)->lte($now)) {
            for ($x = 1; $x <= 5; $x++) {
                factory(Referee::class)->create([
                    'hired_at' => $startingDate,
                ]);
                $this->refereeCount++;
            }
        }
    }
}
