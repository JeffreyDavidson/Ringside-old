<?php

use Carbon\Carbon;
use App\Models\Wrestler;
use Illuminate\Database\Seeder;

class WrestlersTableSeeder extends Seeder
{
    private $wrestlerCount = 1;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startingDate = Carbon::parse('First Monday of January 1990');
        $now = Carbon::now();

        // Create 30 initial wrestlers.
        for ($this->wrestlerCount; $this->wrestlerCount <= 30; $this->wrestlerCount++) {
            factory(Wrestler::class)->create([
                'name' => 'Wrestler '.$this->wrestlerCount,
                'slug' => 'wrestler'.$this->wrestlerCount,
                'hired_at' => $startingDate->hour(mt_rand(9, 18)),
                'signature_move' => 'Signature Move '.$this->wrestlerCount
            ]);
        }

        // Create 5 wrestlers every 4 months until the current date.
        while ($startingDate->addMonths(4)->lte($now)) {
            for ($x = 1; $x <= 5; $x++) {
                factory(Wrestler::class)->create([
                    'name' => 'Wrestler '.$this->wrestlerCount,
                    'slug' => 'wrestler'.$this->wrestlerCount,
                    'hired_at' => $startingDate->hour(mt_rand(9, 18)),
                    'signature_move' => 'Signature Move '.$this->wrestlerCount
                ]);
                $this->wrestlerCount++;
            }
        }
    }
}
