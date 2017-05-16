<?php

use App\Models\Wrestler;
use App\Models\WrestlerBio;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class WrestlersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$lastDate = Carbon::parse('January 2, 1970');
    	$i = 0;
    	while($lastDate->lt(Carbon::now()->subDay(14))) {
    		while($this->chance(30)) {
				$wrestler = factory(Wrestler::class)->states($this->getStatus())->create([
					'name' => 'Wrestler ' . $i++,
					'slug' => 'wrestler' . $i,
					'hired_at' => $lastDate
				]);

				$wrestler->bio()->save(factory(WrestlerBio::class)->create([
					'wrestler_id' => $wrestler->id,
					'signature_move' => 'Signature Move ' . $i
				]));

			}

			$lastDate->addDay(2);
    	}
//        for($i = 1; $i <= 900; $i++)
//        {
//
//
//
//        }
//
//        for($i = 901; $i <= 904; $i++)
//        {
//            $wrestler = factory(Wrestler::class)->states('inactive')->create(['name' => 'Wrestler '. $i, 'slug' => 'wrestler'.$i]);
//
//            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
//        }
//
//        for($i = 905; $i <= 910; $i++)
//        {
//            $wrestler =  factory(Wrestler::class)->states('injured')->create(['name' => 'Wrestler '.$i, 'slug' => 'wrestler'.$i]);
//
//            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
//        }
//
//        for($i = 911; $i <= 914; $i++)
//        {
//            $wrestler = factory(Wrestler::class)->states('suspended')->create(['name' => 'Wrestler '. $i, 'slug' => 'wrestler'.$i]);
//
//            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
//        }
//
//        for($i = 915; $i <= 1000; $i++)
//        {
//            $wrestler = factory(Wrestler::class)->states('retired')->create(['name' => 'Wrestler '. $i, 'slug' => 'wrestler'.$i]);
//
//            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
//        }
    }

	/*
	 * Helpers
	 */
	public function chance(int $percent) {
		return rand(0,100) < $percent;
	}

	public function getStatus() {
		return collect(['active', 'active', 'active', 'active', 'active', 'active', 'inactive', 'injured', 'suspended', 'retired'])->random();
	}
}
