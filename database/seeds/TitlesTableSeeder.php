<?php

use Carbon\Carbon;
use App\Models\Title;
use Illuminate\Database\Seeder;

class TitlesTableSeeder extends Seeder
{
    private $titleCount = 1;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startingDate = Carbon::parse('First Monday of January 1990');
        $now = Carbon::now();

        for ($this->titleCount; $this->titleCount <= 5; $this->titleCount++) {
            factory(Title::class)->create([
                'name' => 'Title '.$this->titleCount,
                'slug' => 'title'.$this->titleCount,
                'introduced_at' => $startingDate,
            ]);
        }

        while ($startingDate->addYears(8)->lte($now)) {
            for ($x = 1; $x <= 1; $x++) {
                factory(Title::class)->create([
                    'name' => 'Title '.$this->titleCount,
                    'slug' => 'title'.$this->titleCount,
                    'introduced_at' => $startingDate,
                ]);
                $this->titleCount++;
            }
        }
    }
}
