<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Manager;
use App\Models\Match;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Title;
use App\Models\User;
use App\Models\Wrestler;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ViewWrestlerBioTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $role;

    private $permission;

    private $wrestler;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->role = factory(Role::class)->create(['slug' => 'admin']);
        $this->permission = factory(Permission::class)->create(['slug' => 'show-wrestler']);
        $this->wrestler = factory(Wrestler::class)->create([
            'name'           => 'Wrestler 1',
            'slug'           => 'wrestler1',
            'hired_at'       => '2017-08-04',
            'hometown'       => 'Kansas City, Missouri',
            'height'         => 73,
            'weight'         => 251,
            'signature_move' => 'Powerbomb',
        ]);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    public function users_who_have_permission_can_view_a_wrestler_bio()
    {
        $response = $this->actingAs($this->user)->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function users_who_dont_have_permission_cannot_view_a_wrestler_bio()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_view_a_wrestler_bio()
    {
        $response = $this->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function view_bio_information_of_wrestler()
    {
        $response = $this->actingAs($this->user)->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertSee('Wrestler 1');
        $response->assertSee('Kansas City, Missouri');
        $response->assertSee('6\'1"');
        $response->assertSee('251 lbs.');
        $response->assertSee('Powerbomb');
    }

    /** @test */
    public function view_list_of_current_managers_on_wrestler_bio()
    {
        $managerA = factory(Manager::class)->create(['first_name' => 'Jane', 'last_name' => 'Doe']);

        $this->wrestler->hireManager($managerA, Carbon::parse('last week'));

        $response = $this->actingAs($this->user)->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(200);
        $response->assertSee('Jane Doe');
    }

    /** @test */
    public function view_list_of_previous_managers_on_wrestler_bio()
    {
        $managerA = factory(Manager::class)->create(['first_name' => 'John', 'last_name' => 'Smith']);

        $this->wrestler->hireManager($managerA, Carbon::parse('last week'));
        $this->wrestler->fireManager($managerA, Carbon::parse('yesterday'));

        $response = $this->actingAs($this->user)->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(200);
        $response->assertSee('John Smith');
    }

    /** @test */
    public function view_list_of_current_titles_held_on_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();
        $titleA = factory(Title::class)->create(['name' => 'Title A']);
        $titleB = factory(Title::class)->create(['name' => 'Title B']);

        $wrestler->winTitle($titleA, Carbon::yesterday());
        $wrestler->winTitle($titleB, Carbon::yesterday());

        $response = $this->actingAs($this->user)->get(route('wrestlers.show', $wrestler->id));

        $response->assertSee('Title A');
        $response->assertSee('Title B');
    }

    /** @test */
    public function view_list_of_previous_titles_held_on_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();
        $titleA = factory(Title::class)->create(['name' => 'Title A']);
        factory(Title::class)->create(['name' => 'Title B']);

        $wrestler->winTitle($titleA, Carbon::yesterday());
        $wrestler->loseTitle($titleA, Carbon::now());

        $response = $this->actingAs($this->user)->get(route('wrestlers.show', $wrestler->id));

        $response->assertSee('Title A');
        $response->assertDontSee('Title B');
    }

    /** @test */
    public function view_list_of_currently_scheduled_matches_on_wrestler_bio()
    {
        $event = factory(Event::class)->create(['name' => 'Event Name', 'date' => Carbon::parse('tomorrow')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);

        $wrestler2 = factory(Wrestler::class)->create([
            'name'     => 'Wrestler 2',
            'hired_at' => Carbon::parse('last month'),
        ]);

        $match->addWrestler($this->wrestler);
        $match->addWrestler($wrestler2);

        $response = $this->actingAs($this->user)->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertSee('Event Name');
    }

    /** @test */
    public function view_list_of_previous_matches_on_wrestler_bio()
    {
        $event = factory(Event::class)->create(['name' => 'Event Name', 'date' => Carbon::parse('last week')]);
        $match = factory(Match::class)->create(['event_id' => $event->id]);

        $wrestler2 = factory(Wrestler::class)->create([
            'name'     => 'Wrestler 2',
            'hired_at' => Carbon::parse('last month'),
        ]);

        $match->addWrestler($this->wrestler);
        $match->addWrestler($wrestler2);

        $response = $this->actingAs($this->user)->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertSee('Event Name');
    }

    //    TODO: Write test for viewing list of retirements
    //    TODO: Write test for viewing list of injuries
}
