<?php

use App\Wrestler;
use App\WrestlerRetire;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WrestlersRetirementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wrestlers = Wrestler::get()->random(5);

        foreach($wrestlers as $wrestler)
        {
            factory(WrestlerRetire::class)->create([
                'wrestler_id' => $wrestler->id,
                'retired_at' => function() use ($wrestler) {
                    return Carbon::parse($wrestler->hired_at)->addYears(3);
                },
                'ended_at' => function(array $retire) {
                    return Carbon::parse($retire['retired_at'])->addMonths(2);
                }
            ]);
        }

        for($i = 1; $i <= 50; $i++)
        {
            factory(WrestlerRetire::class)->create(['wrestler_id' => Wrestler::all()->random()->id]);
        }

        $retiredWrestlerIds = Wrestler::where('status_id', 3)->get()->pluck('id');

        foreach($retiredWrestlerIds as $wrestlerId)
        {
            factory(WrestlerRetire::class)->create(['wrestler_id' => $wrestlerId, 'ended_at' => null]);
        }
    }
}
