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

        $viewRosterMembers = Permission::create(['name' => 'View Roster Members', 'slug' => 'view-roster-members']);
        $createRosterMember = Permission::create(['name' => 'Create Roster Member', 'slug' => 'create-roster-member']);
        $updateRosterMember = Permission::create(['name' => 'Update Roster Member', 'slug' => 'update-roster-member']);
        $viewRosterMember = Permission::create(['name' => 'View Roster Member', 'slug' => 'view-roster-member']);
        $deleteRosterMember = Permission::create(['name' => 'Delete Roster Member', 'slug' => 'delete-roster-member']);
        $activateRosterMember = Permission::create(['name' => 'Activate Roster Member', 'slug' => 'activate-roster-member']);
        $deactivateRosterMember = Permission::create(['name' => 'Deactivate Roster Member', 'slug' => 'deactivate-roster-member']);
        $retireRosterMember = Permission::create(['name' => 'Retire Roster Member', 'slug' => 'retire-roster-member']);
        $unretireRosterMember = Permission::create(['name' => 'Unretire Roster Member', 'slug' => 'unretire-roster-member']);

        $viewTitles = Permission::create(['name' => 'View Titles', 'slug' => 'view-titles']);
        $createTitle = Permission::create(['name' => 'Create Title', 'slug' => 'create-title']);
        $updateTitle = Permission::create(['name' => 'Update Title', 'slug' => 'update-title']);
        $viewTitle = Permission::create(['name' => 'View Title', 'slug' => 'view-title']);
        $deleteTitle = Permission::create(['name' => 'Delete Title', 'slug' => 'delete-title']);
        $activateTitle = Permission::create(['name' => 'Activate Title', 'slug' => 'activate-title']);
        $deactivateTitle = Permission::create(['name' => 'Deactivate Title', 'slug' => 'deactivate-title']);
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

        $admin->givePermissionTo($viewRosterMembers);
        $admin->givePermissionTo($createRosterMember);
        $admin->givePermissionTo($updateRosterMember);
        $admin->givePermissionTo($viewRosterMember);
        $admin->givePermissionTo($deleteRosterMember);
        $admin->givePermissionTo($activateRosterMember);
        $admin->givePermissionTo($deactivateRosterMember);
        $admin->givePermissionTo($retireRosterMember);
        $admin->givePermissionTo($unretireRosterMember);

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

        $superAdmin->givePermissionTo($viewRosterMembers);
        $superAdmin->givePermissionTo($createRosterMember);
        $superAdmin->givePermissionTo($updateRosterMember);
        $superAdmin->givePermissionTo($viewRosterMember);
        $superAdmin->givePermissionTo($deleteRosterMember);
        $superAdmin->givePermissionTo($activateRosterMember);
        $superAdmin->givePermissionTo($deactivateRosterMember);
        $superAdmin->givePermissionTo($retireRosterMember);
        $superAdmin->givePermissionTo($unretireRosterMember);

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
