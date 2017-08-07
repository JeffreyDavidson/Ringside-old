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
        $user = Role::create(['name' => 'User',  'slug' => 'user']);
        $editor = Role::create(['name' => 'Editor',  'slug' => 'editor']);
        $admin = Role::create(['name' => 'Admin',  'slug' => 'admin']);
        $superAdmin = Role::create(['name' => 'Super Administrator',  'slug' => 'super-admin']);

        $viewTitles = Permission::create(['name' => 'View Titles',  'slug' => 'view-titles']);
        $createTitle = Permission::create(['name' => 'Create Title',  'slug' => 'create-title']);
        $editTitle = Permission::create(['name' => 'Edit Title',  'slug' => 'edit-title']);
        $showTitle = Permission::create(['name' => 'Show Title',  'slug' => 'show-title']);
        $deleteTitle = Permission::create(['name' => 'Delete Title',  'slug' => 'delete-title']);

        $viewStipulations = Permission::create(['name' => 'View Stipulations',  'slug' => 'view-stipulations']);
        $createStipulation = Permission::create(['name' => 'Create Stipulation',  'slug' => 'create-stipulation']);
        $editStipulation = Permission::create(['name' => 'Edit Stipulation',  'slug' => 'edit-stipulation']);
        $showStipulation = Permission::create(['name' => 'Show Stipulation',  'slug' => 'show-stipulation']);
        $deleteStipulation = Permission::create(['name' => 'Delete Stipulation',  'slug' => 'delete-stipulation']);

        $viewVenues = Permission::create(['name' => 'View Venues',  'slug' => 'view-venues']);
        $createVenue = Permission::create(['name' => 'Create Venue',  'slug' => 'create-venue']);
        $editVenue = Permission::create(['name' => 'Edit Venue',  'slug' => 'edit-venue']);
        $showVenue = Permission::create(['name' => 'Show Venue',  'slug' => 'show-venue']);
        $deleteVenue = Permission::create(['name' => 'Delete Venue',  'slug' => 'delete-venue']);

        $admin->givePermissionTo($viewTitles);
        $admin->givePermissionTo($createTitle);
        $admin->givePermissionTo($editTitle);
        $admin->givePermissionTo($showTitle);
        $admin->givePermissionTo($deleteTitle);
        $admin->givePermissionTo($viewStipulations);
        $admin->givePermissionTo($createStipulation);
        $admin->givePermissionTo($editStipulation);
        $admin->givePermissionTo($showStipulation);
        $admin->givePermissionTo($deleteStipulation);
        $admin->givePermissionTo($viewVenues);
        $admin->givePermissionTo($createVenue);
        $admin->givePermissionTo($editVenue);
        $admin->givePermissionTo($showVenue);
        $admin->givePermissionTo($deleteVenue);


        $superAdmin->givePermissionTo($viewVenues);
        $superAdmin->givePermissionTo($createVenue);
        $superAdmin->givePermissionTo($editVenue);
        $superAdmin->givePermissionTo($showVenue);
        $superAdmin->givePermissionTo($deleteVenue);
        $superAdmin->givePermissionTo($viewStipulations);
        $superAdmin->givePermissionTo($createStipulation);
        $superAdmin->givePermissionTo($editStipulation);
        $superAdmin->givePermissionTo($showStipulation);
        $superAdmin->givePermissionTo($deleteStipulation);
    }
}
