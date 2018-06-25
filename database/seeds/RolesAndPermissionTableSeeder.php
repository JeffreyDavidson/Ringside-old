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
        $storeWrestler = Permission::create(['name' => 'Store Wrestler',  'slug' => 'store-wrestler']);
        $editWrestler = Permission::create(['name' => 'Edit Wrestler',  'slug' => 'edit-wrestler']);
        $updateWrestler = Permission::create(['name' => 'Update Wrestler',  'slug' => 'update-wrestler']);
        $showWrestler = Permission::create(['name' => 'Show Wrestler',  'slug' => 'show-wrestler']);
        $deleteWrestler = Permission::create(['name' => 'Delete Wrestler',  'slug' => 'delete-wrestler']);
        $retireWrestler = Permission::create(['name' => 'Retire Wrestler',  'slug' => 'retire-wrestler']);

        $viewTitles = Permission::create(['name' => 'View Titles',  'slug' => 'view-titles']);
        $createTitle = Permission::create(['name' => 'Create Title',  'slug' => 'create-title']);
        $storeTitle = Permission::create(['name' => 'Store Title',  'slug' => 'store-title']);
        $editTitle = Permission::create(['name' => 'Edit Title',  'slug' => 'edit-title']);
        $updateTitle = Permission::create(['name' => 'Update Title',  'slug' => 'update-title']);
        $showTitle = Permission::create(['name' => 'Show Title',  'slug' => 'show-title']);
        $deleteTitle = Permission::create(['name' => 'Delete Title',  'slug' => 'delete-title']);
        $retireTitle = Permission::create(['name' => 'Retire Title',  'slug' => 'retire-title']);

        $viewStipulations = Permission::create(['name' => 'View Stipulations',  'slug' => 'view-stipulations']);
        $createStipulation = Permission::create(['name' => 'Create Stipulation',  'slug' => 'create-stipulation']);
        $storeStipulation = Permission::create(['name' => 'Store Stipulation',  'slug' => 'store-stipulation']);
        $editStipulation = Permission::create(['name' => 'Edit Stipulation',  'slug' => 'edit-stipulation']);
        $updateStipulation = Permission::create(['name' => 'Update Stipulation',  'slug' => 'update-stipulation']);
        $showStipulation = Permission::create(['name' => 'Show Stipulation',  'slug' => 'show-stipulation']);
        $deleteStipulation = Permission::create(['name' => 'Delete Stipulation',  'slug' => 'delete-stipulation']);

        $viewVenues = Permission::create(['name' => 'View Venues',  'slug' => 'view-venues']);
        $createVenue = Permission::create(['name' => 'Create Venue',  'slug' => 'create-venue']);
        $storeVenue = Permission::create(['name' => 'Store Venue',  'slug' => 'store-venue']);
        $editVenue = Permission::create(['name' => 'Edit Venue',  'slug' => 'edit-venue']);
        $updateVenue = Permission::create(['name' => 'Update Venue',  'slug' => 'update-venue']);
        $showVenue = Permission::create(['name' => 'Show Venue',  'slug' => 'show-venue']);
        $deleteVenue = Permission::create(['name' => 'Delete Venue',  'slug' => 'delete-venue']);

        $viewEvent = Permission::create(['name' => 'View Events',  'slug' => 'view-events']);
        $createEvent = Permission::create(['name' => 'Create Event',  'slug' => 'create-event']);
        $storeEvent = Permission::create(['name' => 'Store Event',  'slug' => 'store-event']);
        $editEvent = Permission::create(['name' => 'Edit Event',  'slug' => 'edit-event']);
        $updateEvent = Permission::create(['name' => 'Update Event',  'slug' => 'update-event']);
        $showEvent = Permission::create(['name' => 'Show Event',  'slug' => 'show-event']);
        $deleteEvent = Permission::create(['name' => 'Delete Event',  'slug' => 'delete-event']);
        $archiveEvent = Permission::create(['name' => 'Archive Event',  'slug' => 'archive-event']);
        $editResultsForEvent = Permission::create(['name' => 'Edit Event Results',  'slug' => 'edit-event-results']);

        $admin->givePermissionTo($viewWrestlers);
        $admin->givePermissionTo($createWrestler);
        $admin->givePermissionTo($storeWrestler);
        $admin->givePermissionTo($editWrestler);
        $admin->givePermissionTo($updateWrestler);
        $admin->givePermissionTo($showWrestler);
        $admin->givePermissionTo($deleteWrestler);
        $admin->givePermissionTo($retireWrestler);

        $admin->givePermissionTo($viewTitles);
        $admin->givePermissionTo($createTitle);
        $admin->givePermissionTo($storeTitle);
        $admin->givePermissionTo($editTitle);
        $admin->givePermissionTo($updateTitle);
        $admin->givePermissionTo($showTitle);
        $admin->givePermissionTo($deleteTitle);
        $admin->givePermissionTo($retireTitle);

        $admin->givePermissionTo($viewStipulations);
        $admin->givePermissionTo($createStipulation);
        $admin->givePermissionTo($storeStipulation);
        $admin->givePermissionTo($editStipulation);
        $admin->givePermissionTo($updateStipulation);
        $admin->givePermissionTo($showStipulation);
        $admin->givePermissionTo($deleteStipulation);

        $admin->givePermissionTo($viewVenues);
        $admin->givePermissionTo($createVenue);
        $admin->givePermissionTo($storeVenue);
        $admin->givePermissionTo($editVenue);
        $admin->givePermissionTo($updateVenue);
        $admin->givePermissionTo($showVenue);
        $admin->givePermissionTo($deleteVenue);

        $admin->givePermissionTo($viewEvent);
        $admin->givePermissionTo($createEvent);
        $admin->givePermissionTo($storeEvent);
        $admin->givePermissionTo($editEvent);
        $admin->givePermissionTo($updateEvent);
        $admin->givePermissionTo($showEvent);
        $admin->givePermissionTo($deleteEvent);
        $admin->givePermissionTo($archiveEvent);
        $admin->givePermissionTo($editResultsForEvent);

        $superAdmin->givePermissionTo($viewWrestlers);
        $superAdmin->givePermissionTo($createWrestler);
        $superAdmin->givePermissionTo($storeWrestler);
        $superAdmin->givePermissionTo($editWrestler);
        $superAdmin->givePermissionTo($updateWrestler);
        $superAdmin->givePermissionTo($showWrestler);
        $superAdmin->givePermissionTo($deleteWrestler);
        $superAdmin->givePermissionTo($retireWrestler);

        $superAdmin->givePermissionTo($viewTitles);
        $superAdmin->givePermissionTo($createTitle);
        $superAdmin->givePermissionTo($storeTitle);
        $superAdmin->givePermissionTo($editTitle);
        $superAdmin->givePermissionTo($updateTitle);
        $superAdmin->givePermissionTo($showTitle);
        $superAdmin->givePermissionTo($deleteTitle);
        $superAdmin->givePermissionTo($retireTitle);

        $superAdmin->givePermissionTo($viewStipulations);
        $superAdmin->givePermissionTo($createStipulation);
        $superAdmin->givePermissionTo($storeStipulation);
        $superAdmin->givePermissionTo($editStipulation);
        $superAdmin->givePermissionTo($updateStipulation);
        $superAdmin->givePermissionTo($showStipulation);
        $superAdmin->givePermissionTo($deleteStipulation);

        $superAdmin->givePermissionTo($viewVenues);
        $superAdmin->givePermissionTo($createVenue);
        $superAdmin->givePermissionTo($storeVenue);
        $superAdmin->givePermissionTo($editVenue);
        $superAdmin->givePermissionTo($updateVenue);
        $superAdmin->givePermissionTo($showVenue);
        $superAdmin->givePermissionTo($deleteVenue);

        $superAdmin->givePermissionTo($viewEvent);
        $superAdmin->givePermissionTo($createEvent);
        $superAdmin->givePermissionTo($storeEvent);
        $superAdmin->givePermissionTo($editEvent);
        $superAdmin->givePermissionTo($updateEvent);
        $superAdmin->givePermissionTo($showEvent);
        $superAdmin->givePermissionTo($deleteEvent);
        $superAdmin->givePermissionTo($archiveEvent);
    }
}
