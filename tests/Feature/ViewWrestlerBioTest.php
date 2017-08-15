<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Wrestler;
use App\Models\Manager;
use App\Models\Title;
use App\Models\Match;
use App\Models\Event;
use App\Models\WrestlerBio;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
            'name' => 'Wrestler 1',
            'slug' => 'wrestler1',
            'hired_at' => '2017-08-04',
            'hometown' => 'Kansas City, Missouri',
            'height' => 73,
            'weight' => 251,
            'signature_move' => 'Powerbomb'
        ]);

        $this->role->givePermissionTo($this->permission);
        $this->user->assignRole($this->role);
    }

    /** @test */
    function users_who_have_permission_can_view_a_wrestler_bio()
    {
        $response = $this->actingAs($this->user)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(200);
    }

    /** @test */
    function users_who_dont_have_permission_cannot_view_a_wrestler_bio()
    {
        $userWithoutPermission = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'editor']);
        $userWithoutPermission->assignRole($role);

        $response = $this->actingAs($userWithoutPermission)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(403);
    }

    /** @test */
    function guests_cannot_view_the_add_stipulation_form()
    {
        $response = $this->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function view_bio_information_of_wrestler()
    {
        $response = $this->actingAs($this->user)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertSee('Wrestler 1');
        $response->assertSee('Kansas City, Missouri');
        $response->assertSee('6\'1"');
        $response->assertSee('251 lbs.');
        $response->assertSee('Powerbomb');
    }

    /** @test */
    public function view_list_of_managers_on_wrestler_bio()
    {
        $firedManagerA = factory(Manager::class)->create(['name' => 'Fired Manager A']);
        $firedManagerB = factory(Manager::class)->create(['name' => 'Fired Manager B']);
        $hiredManager = factory(Manager::class)->create(['name' => 'Hired Manager']);

        $this->wrestler->hireManager($firedManagerA, Carbon::now());
        $this->wrestler->fireManager($firedManager, Carbon::now('+1 day'));
        $this->wrestler->hireManager($hiredManager, Carbon::now('+2 days'));

        $response = $this->actingAs($this->user)
                        ->get(route('wrestlers.show', $this->wrestler->id));

        $response->assertStatus(200);
        $response->assertSee('Fired Manager');
        $response->assertSee('Hired Manager');
    }

    /** @test */
    public function view_list_of_titles_held_on_wrestler_bio()
    {
        $wrestler = factory(Wrestler::class)->create();

        $wrestler->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler->id]));

        $title1 = factory(Title::class)->create(['name' => 'Title 1']);
        $title2 = factory(Title::class)->create(['name' => 'Title 2']);

        $wrestler->winTitle($title1, Carbon::parse('-3 days'));
        $wrestler->loseTitle($title1, Carbon::parse('-2 days'));
        $wrestler->winTitle($title1, Carbon::parse('-2 days'));
        $wrestler->loseTitle($title1, Carbon::parse('-1 day'));
        $wrestler->winTitle($title2, Carbon::parse('-1 day'));

        $wrestler->titles->map(function($i) { return $i->title->name; });

        $this->visit('wrestlers/'.$wrestler->id);

        $this->see('Title 1 (2x)');
        $this->see('Title 2');
    }

    /** @test */
    public function view_list_of_matches_on_wrestler_bio()
    {
        $event = factory(Event::class)->create(['name' => 'My Event']);
        $match = factory(Match::class)->create(['event_id' => $event->id]);
        $wrestler1 = factory(Wrestler::class)->create(['name' => 'Wrestler 1']);

        $wrestler1->bio()->save(factory(WrestlerBio::class)->create(['wrestler_id' => $wrestler1->id]));

        $wrestler2 = factory(Wrestler::class)->create(['name' => 'Wrestler 2']);

        $match->addWrestler($wrestler1);
        $match->addWrestler($wrestler2);

        $this->visit('wrestlers/'.$wrestler1->id);
        $this->see('My Event');
    }
}
