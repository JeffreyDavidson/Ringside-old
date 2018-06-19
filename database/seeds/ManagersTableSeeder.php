<?php

use Carbon\Carbon;
use App\Models\Manager;
use Illuminate\Database\Seeder;

class ManagersTableSeeder extends Seeder
{
    private $managerCount = 1;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startingDate = Carbon::parse('First Monday of January 1990');
        $now = Carbon::now();

        for ($this->managerCount; $this->managerCount <= 5; $this->managerCount++) {
            factory(Manager::class)->create([
                'hired_at' => $startingDate->hour(mt_rand(9, 18)),
            ]);
        }

        while ($startingDate->addYears(2)->lte($now)) {
            for ($x = 1; $x <= 3; $x++) {
                factory(Manager::class)->create([
                    'hired_at' => $startingDate->hour(mt_rand(9, 18)),
                ]);
                $this->managerCount++;
            }
        }

        while ($startingDate->addYears(2)->lte(Carbon::now())) {
            factory(Manager::class, 3)->create([
                'hired_at' => $startingDate->hour(mt_rand(9, 18)),
            ]);
        }
    }
}
