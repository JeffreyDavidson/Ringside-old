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
        factory(Role::class)->create(['name' => 'User',  'slug' => 'user']);
        factory(Role::class)->create(['name' => 'Editor',  'slug' => 'editor']);
        $admin = factory(Role::class)->create(['name' => 'Admin',  'slug' => 'admin']);
        $superAdmin = factory(Role::class)->create(['name' => 'Super Administrator',  'slug' => 'super-admin']);

        $createVenue = factory(Permission::class)->create(['name' => 'Create A Venue',  'slug' => 'create-venue']);
        $createStipulation = factory(Permission::class)->create(['name' => 'Create A Stipulation',  'slug' => 'create-stipulation']);

        $admin->givePermissionTo($createVenue);
        $admin->givePermissionTo($createStipulation);

        $superAdmin->givePermissionTo($createVenue);
        $superAdmin->givePermissionTo($createStipulation);
    }
}
