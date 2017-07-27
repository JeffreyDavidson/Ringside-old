<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Role::class)->create(['name' => 'Wrestler',  'slug' => 'wrestler']);
        factory(Role::class)->create(['name' => 'Editor',  'slug' => 'editor']);
        factory(Role::class)->create(['name' => 'Admin',  'slug' => 'admin']);
        factory(Role::class)->create(['name' => 'Super Administrator',  'slug' => 'super_admin']);

        factory(Permission::class)->create(['name' => 'Create A Venue',  'slug' => 'create-venue']);


    }
}
