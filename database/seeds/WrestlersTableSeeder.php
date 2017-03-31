<?php

use App\Wrestler;
use App\WrestlerBio;
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
        for($i = 1; $i <= 100; $i++)
        {
            $wrestler =  factory(Wrestler::class)->states('active')->create(['name' => 'Wrestler '.$i, 'slug' => 'wrestler'.$i]);

            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
        }

        for($i = 101; $i <= 110; $i++)
        {
            $wrestler = factory(Wrestler::class)->states('inactive')->create(['name' => 'Wrestler '. $i, 'slug' => 'wrestler'.$i]);

            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
        }

        for($i = 111; $i <= 115; $i++)
        {
            $wrestler = factory(Wrestler::class)->states('suspended')->create(['name' => 'Wrestler '. $i, 'slug' => 'wrestler'.$i]);

            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
        }

        for($i = 116; $i <= 500; $i++)
        {
            $wrestler = factory(Wrestler::class)->states('retired')->create(['name' => 'Wrestler '. $i, 'slug' => 'wrestler'.$i]);

            $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id, 'signature_move' => 'Signature Move '.$i]));
        }
    }
}
