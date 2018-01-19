<?php

use App\Models\Role;
use App\Models\Permission;
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

        $viewWrestlers = Permission::create(['name' => 'View Wrestlers',  'slug' => 'view-wrestlers']);
        $createWrestler = Permission::create(['name' => 'Create Wrestler',  'slug' => 'create-wrestler']);
        $editWrestler = Permission::create(['name' => 'Edit Wrestler',  'slug' => 'edit-wrestler']);
        $showWrestler = Permission::create(['name' => 'Show Wrestler',  'slug' => 'show-wrestler']);
        $deleteWrestler = Permission::create(['name' => 'Delete Wrestler',  'slug' => 'delete-wrestler']);

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

        $viewEvent = Permission::create(['name' => 'View Events',  'slug' => 'view-events']);
        $createEvent = Permission::create(['name' => 'Create Event',  'slug' => 'create-event']);
        $editEvent = Permission::create(['name' => 'Edit Event',  'slug' => 'edit-event']);
        $showEvent = Permission::create(['name' => 'Show Event',  'slug' => 'show-event']);
        $deleteEvent = Permission::create(['name' => 'Delete Event',  'slug' => 'delete-event']);

        $admin->givePermissionTo($viewWrestlers);
        $admin->givePermissionTo($createWrestler);
        $admin->givePermissionTo($editWrestler);
        $admin->givePermissionTo($showWrestler);
        $admin->givePermissionTo($deleteWrestler);
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
        $admin->givePermissionTo($viewEvent);
        $admin->givePermissionTo($createEvent);
        $admin->givePermissionTo($editEvent);
        $admin->givePermissionTo($showEvent);
        $admin->givePermissionTo($deleteEvent);

        $superAdmin->givePermissionTo($viewWrestlers);
        $superAdmin->givePermissionTo($createWrestler);
        $superAdmin->givePermissionTo($editWrestler);
        $superAdmin->givePermissionTo($showWrestler);
        $superAdmin->givePermissionTo($deleteWrestler);
        $superAdmin->givePermissionTo($viewTitles);
        $superAdmin->givePermissionTo($createTitle);
        $superAdmin->givePermissionTo($editTitle);
        $superAdmin->givePermissionTo($showTitle);
        $superAdmin->givePermissionTo($deleteTitle);
        $superAdmin->givePermissionTo($viewStipulations);
        $superAdmin->givePermissionTo($createStipulation);
        $superAdmin->givePermissionTo($editStipulation);
        $superAdmin->givePermissionTo($showStipulation);
        $superAdmin->givePermissionTo($deleteStipulation);
        $superAdmin->givePermissionTo($viewVenues);
        $superAdmin->givePermissionTo($createVenue);
        $superAdmin->givePermissionTo($editVenue);
        $superAdmin->givePermissionTo($showVenue);
        $superAdmin->givePermissionTo($deleteVenue);
        $superAdmin->givePermissionTo($viewEvent);
        $superAdmin->givePermissionTo($createEvent);
        $superAdmin->givePermissionTo($editEvent);
        $superAdmin->givePermissionTo($showEvent);
        $superAdmin->givePermissionTo($deleteEvent);
    }
}
