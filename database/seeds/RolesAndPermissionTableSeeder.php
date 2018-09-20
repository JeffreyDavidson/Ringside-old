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
        $user = Role::create(['name' => 'User', 'slug' => 'user']);
        $editor = Role::create(['name' => 'Editor', 'slug' => 'editor']);
        $admin = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $superAdmin = Role::create(['name' => 'Super Administrator', 'slug' => 'super-admin']);

        $viewWrestlers = Permission::create(['name' => 'View Wrestlers', 'slug' => 'view-wrestlers']);
        $createWrestler = Permission::create(['name' => 'Create Wrestler', 'slug' => 'create-wrestler']);
        $updateWrestler = Permission::create(['name' => 'Update Wrestler', 'slug' => 'update-wrestler']);
        $viewWrestler = Permission::create(['name' => 'View Wrestler', 'slug' => 'view-wrestler']);
        $deleteWrestler = Permission::create(['name' => 'Delete Wrestler', 'slug' => 'delete-wrestler']);
        $activeWrestler = Permission::create(['name' => 'Activate Wrestler', 'slug' => 'activate-wrestler']);
        $deactiveWrestler = Permission::create(['name' => 'Deactivate Wrestler', 'slug' => 'deactivate-wrestler']);
        $retireWrestler = Permission::create(['name' => 'Retire Wrestler', 'slug' => 'retire-wrestler']);
        $unretireWrestler = Permission::create(['name' => 'Unretire Wrestler', 'slug' => 'unretire-wrestler']);

        $viewTitles = Permission::create(['name' => 'View Titles', 'slug' => 'view-titles']);
        $createTitle = Permission::create(['name' => 'Create Title', 'slug' => 'create-title']);
        $updateTitle = Permission::create(['name' => 'Update Title', 'slug' => 'update-title']);
        $viewTitle = Permission::create(['name' => 'View Title', 'slug' => 'view-title']);
        $deleteTitle = Permission::create(['name' => 'Delete Title', 'slug' => 'delete-title']);
        $activeTitle = Permission::create(['name' => 'Activate Title', 'slug' => 'activate-title']);
        $deactiveTitle = Permission::create(['name' => 'Deactivate Title', 'slug' => 'deactivat-title']);
        $retireTitle = Permission::create(['name' => 'Retire Title', 'slug' => 'retire-title']);
        $unretireTitle = Permission::create(['name' => 'Unretire Title', 'slug' => 'unretire-title']);

        $viewStipulations = Permission::create(['name' => 'View Stipulations', 'slug' => 'view-stipulations']);
        $createStipulation = Permission::create(['name' => 'Create Stipulation', 'slug' => 'create-stipulation']);
        $updateStipulation = Permission::create(['name' => 'Update Stipulation', 'slug' => 'update-stipulation']);
        $viewStipulation = Permission::create(['name' => 'View Stipulation', 'slug' => 'view-stipulation']);
        $deleteStipulation = Permission::create(['name' => 'Delete Stipulation', 'slug' => 'delete-stipulation']);

        $viewVenues = Permission::create(['name' => 'View Venues', 'slug' => 'view-venues']);
        $createVenue = Permission::create(['name' => 'Create Venue', 'slug' => 'create-venue']);
        $updateVenue = Permission::create(['name' => 'Update Venue', 'slug' => 'update-venue']);
        $viewVenue = Permission::create(['name' => 'View Venue', 'slug' => 'view-venue']);
        $deleteVenue = Permission::create(['name' => 'Delete Venue', 'slug' => 'delete-venue']);

        $viewEvents = Permission::create(['name' => 'View Events', 'slug' => 'view-events']);
        $createEvent = Permission::create(['name' => 'Create Event', 'slug' => 'create-event']);
        $updateEvent = Permission::create(['name' => 'Update Event', 'slug' => 'update-event']);
        $viewEvent = Permission::create(['name' => 'View Event', 'slug' => 'view-event']);
        $deleteEvent = Permission::create(['name' => 'Delete Event', 'slug' => 'delete-event']);
        $archiveEvent = Permission::create(['name' => 'Archive Event', 'slug' => 'archive-event']);
        $unarchiveEvent = Permission::create(['name' => 'Unarchive Event', 'slug' => 'unarchive-event']);
        $updateEventResults = Permission::create(['name' => 'Update Event Results', 'slug' => 'update-event-results']);

        $createMatch = Permission::create(['name' => 'Create Match', 'slug' => 'create-match']);
        $viewMatch = Permission::create(['name' => 'View Match', 'slug' => 'view-match']);
        $updateMatch = Permission::create(['name' => 'Update Match', 'slug' => 'update-match']);
        $deleteMatch = Permission::create(['name' => 'Delete Match', 'slug' => 'delete-match']);

        $admin->givePermissionTo($viewWrestlers);
        $admin->givePermissionTo($createWrestler);
        $admin->givePermissionTo($updateWrestler);
        $admin->givePermissionTo($viewWrestler);
        $admin->givePermissionTo($deleteWrestler);
        $admin->givePermissionTo($activateWrestler);
        $admin->givePermissionTo($deactivateWrestler);
        $admin->givePermissionTo($retireWrestler);
        $admin->givePermissionTo($unretireWrestler);

        $admin->givePermissionTo($viewTitles);
        $admin->givePermissionTo($createTitle);
        $admin->givePermissionTo($updateTitle);
        $admin->givePermissionTo($viewTitle);
        $admin->givePermissionTo($deleteTitle);
        $admin->givePermissionTo($activateTitle);
        $admin->givePermissionTo($deactivateTitle);
        $admin->givePermissionTo($retireTitle);
        $admin->givePermissionTo($unretireTitle);

        $admin->givePermissionTo($viewStipulations);
        $admin->givePermissionTo($createStipulation);
        $admin->givePermissionTo($updateStipulation);
        $admin->givePermissionTo($viewStipulation);
        $admin->givePermissionTo($deleteStipulation);

        $admin->givePermissionTo($viewVenues);
        $admin->givePermissionTo($createVenue);
        $admin->givePermissionTo($updateVenue);
        $admin->givePermissionTo($viewVenue);
        $admin->givePermissionTo($deleteVenue);

        $admin->givePermissionTo($viewEvents);
        $admin->givePermissionTo($createEvent);
        $admin->givePermissionTo($updateEvent);
        $admin->givePermissionTo($viewEvent);
        $admin->givePermissionTo($deleteEvent);
        $admin->givePermissionTo($archiveEvent);
        $admin->givePermissionTo($unarchiveEvent);
        $admin->givePermissionTo($updateEventResults);

        $admin->givePermissionTo($createMatch);
        $admin->givePermissionTo($updateMatch);
        $admin->givePermissionTo($viewMatch);
        $admin->givePermissionTo($deleteMatch);

        $superAdmin->givePermissionTo($viewWrestlers);
        $superAdmin->givePermissionTo($createWrestler);
        $superAdmin->givePermissionTo($updateWrestler);
        $superAdmin->givePermissionTo($viewWrestler);
        $superAdmin->givePermissionTo($deleteWrestler);
        $superAdmin->givePermissionTo($activateWrestler);
        $superAdmin->givePermissionTo($deactivateWrestler);
        $superAdmin->givePermissionTo($retireWrestler);
        $superAdmin->givePermissionTo($unretireWrestler);

        $superAdmin->givePermissionTo($viewTitles);
        $superAdmin->givePermissionTo($createTitle);
        $superAdmin->givePermissionTo($updateTitle);
        $superAdmin->givePermissionTo($viewTitle);
        $superAdmin->givePermissionTo($deleteTitle);
        $superAdmin->givePermissionTo($activateTitle);
        $superAdmin->givePermissionTo($deactivateTitle);
        $superAdmin->givePermissionTo($retireTitle);
        $superAdmin->givePermissionTo($unretireTitle);

        $superAdmin->givePermissionTo($viewStipulations);
        $superAdmin->givePermissionTo($createStipulation);
        $superAdmin->givePermissionTo($updateStipulation);
        $superAdmin->givePermissionTo($viewStipulation);
        $superAdmin->givePermissionTo($deleteStipulation);

        $superAdmin->givePermissionTo($viewVenues);
        $superAdmin->givePermissionTo($createVenue);
        $superAdmin->givePermissionTo($updateVenue);
        $superAdmin->givePermissionTo($viewVenue);
        $superAdmin->givePermissionTo($deleteVenue);

        $superAdmin->givePermissionTo($viewEvents);
        $superAdmin->givePermissionTo($createEvent);
        $superAdmin->givePermissionTo($updateEvent);
        $superAdmin->givePermissionTo($viewEvent);
        $superAdmin->givePermissionTo($deleteEvent);
        $superAdmin->givePermissionTo($archiveEvent);
        $superAdmin->givePermissionTo($unarchiveEvent);
        $superAdmin->givePermissionTo($updateEventResults);

        $superAdmin->givePermissionTo($createMatch);
        $superAdmin->givePermissionTo($updateMatch);
        $superAdmin->givePermissionTo($viewMatch);
        $superAdmin->givePermissionTo($deleteMatch);
    }
}
