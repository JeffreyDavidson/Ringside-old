<?php

use App\Models\Wrestler;
use App\Models\WrestlerBio;
use Carbon\Carbon;
use Carbon\CarbonInterval;
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
        $startingDate = Carbon::parse('First Monday of January 2000');
        $now = Carbon::now();

        for ($this->wrestlerCount; $this->wrestlerCount <= 50; $this->wrestlerCount++) {
            factory(Wrestler::class)->create([
                'name' => 'Wrestler '.$this->wrestlerCount,
                'slug' => 'wrestler'.$this->wrestlerCount,
                'hired_at' => $startingDate,
                'signature_move' => 'Signature Move '.$this->wrestlerCount
            ]);
        }

        while ($startingDate->addMonths(4)->lte($now))
        {
            for ($x = 1; $x <= 5; $x++) {
                factory(Wrestler::class)->create([
                    'name' => 'Wrestler ' . $this->wrestlerCount,
                    'slug' => 'wrestler' . $this->wrestlerCount,
                    'hired_at' => $startingDate,
                    'signature_move' => 'Signature Move ' . $this->wrestlerCount
                ]);
                $this->wrestlerCount++;
            }
        }
    }
}
