<?php

use App\Wrestler;
use App\WrestlerInjury;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WrestlersInjuriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wrestlers = Wrestler::get(['id', 'hired_at'])->random(50);

        foreach($wrestlers as $wrestler)
        {
            factory(WrestlerInjury::class)->create([
                'wrestler_id' => $wrestler->id,
                'injured_at' => function() use ($wrestler) {
                    return Carbon::parse($wrestler->hired_at)->addDays(30);
                },
                'healed_at' => function(array $injury) {
                    return Carbon::parse($injury['injured_at'])->addDays(30);
                }
            ]);
        }

        $injuredWrestlerIds = Wrestler::where('status_id', 3)->get()->pluck('id');

        foreach($injuredWrestlerIds as $wrestlerId)
        {
            factory(WrestlerInjury::class)->create(['wrestler_id' => $wrestlerId, 'healed_at' => null]);
        }
    }
}
