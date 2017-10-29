<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected $toTruncate = [
        'roles',
        'permissions',
        'permission_role',
        'users',
        'wrestler_statuses',
        'wrestlers',
        'injuries',
        'retirements',
        'suspensions',
        'titles',
        'champions',
        'managers',
        'events',
        'matches',
        'match_types',
        'stipulations',
        'match_decisions',
        'match_wrestler',
        'match_stipulation',
        'match_title',
        'venues',
        'referees',
        'match_referee'
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
        $this->call(RolesAndPermissionTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(WrestlerStatusesTableSeeder::class);
        $this->call(WrestlersTableSeeder::class);
        $this->call(RefereesTableSeeder::class);
        $this->call(WrestlersInjuriesTableSeeder::class);
        $this->call(WrestlersRetirementsTableSeeder::class);
        $this->call(MatchTypesTableSeeder::class);
        $this->call(StipulationsTableSeeder::class);
        $this->call(MatchDecisionsTableSeeder::class);
        $this->call(VenuesTableSeeder::class);
        $this->call(ManagersTableSeeder::class);
        $this->call(TitlesTableSeeder::class);
        $this->call(EventsTableSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
