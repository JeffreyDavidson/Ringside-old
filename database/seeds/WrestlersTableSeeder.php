<?php

use App\Models\Wrestler;
use App\Models\WrestlerBio;
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
        for($i = 1; $i <= 30; $i++)
        {
            $wrestler =  factory(Wrestler::class)->states('active')->create(['name' => 'Wrestler '.$i, 'slug' => 'wrestler'.$i]);

            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
        }

        for($i = 31; $i <= 34; $i++)
        {
            $wrestler = factory(Wrestler::class)->states('inactive')->create(['name' => 'Wrestler '. $i, 'slug' => 'wrestler'.$i]);

            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
        }

        for($i = 35; $i <= 40; $i++)
        {
            $wrestler =  factory(Wrestler::class)->states('injured')->create(['name' => 'Wrestler '.$i, 'slug' => 'wrestler'.$i]);

            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
        }

        for($i = 41; $i <= 44; $i++)
        {
            $wrestler = factory(Wrestler::class)->states('suspended')->create(['name' => 'Wrestler '. $i, 'slug' => 'wrestler'.$i]);

            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
        }

        for($i = 45; $i <= 1000; $i++)
        {
            $wrestler = factory(Wrestler::class)->states('retired')->create(['name' => 'Wrestler '. $i, 'slug' => 'wrestler'.$i]);

            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
        }
    }
}
