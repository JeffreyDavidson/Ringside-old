<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected $toTruncate = [
        'wrestler_statuses',
        'wrestlers',
        'wrestler_bios',
        'wrestler_injuries',
        'wrestler_retirements',
        'titles',
        'title_wrestler',
        'managers',
        'events',
        'matches',
        'match_types',
        'match_stipulations',
        'match_decisions',
        'match_wrestler',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($this->toTruncate as $table) {
            DB::table($table)->truncate();
        }

        $this->call(WrestlerStatusesTableSeeder::class);
        $this->call(WrestlersTableSeeder::class);
        $this->call(WrestlersInjuriesTableSeeder::class);
        $this->call(WrestlersRetirementsTableSeeder::class);
        $this->call(TitlesTableSeeder::class);
        $this->call(MatchTypesTableSeeder::class);
        $this->call(MatchStipulationsTableSeeder::class);
        $this->call(MatchDecisionsTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(MatchesTableSeeder::class);
//        $this->call(ManagersTableSeeder::class);
//        $this->call(TitleHistoryTableSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
