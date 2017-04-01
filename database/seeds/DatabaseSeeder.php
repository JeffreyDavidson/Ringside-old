<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected $toTruncate = ['wrestler_statuses', 'wrestlers', 'wrestler_bios', 'wrestler_injuries', 'wrestler_retirements', 'titles', 'title_wrestler'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->toTruncate as $table) {
            DB::table($table)->truncate();
        }

        $this->call(WrestlerStatusesTableSeeder::class);
        $this->call(WrestlersTableSeeder::class);
        $this->call(WrestlersInjuriesTableSeeder::class);
        $this->call(WrestlersRetirementsTableSeeder::class);
        $this->call(TitlesTableSeeder::class);
        $this->call(TitleHistoryTableSeeder::class);
    }
}
