<?php

namespace Tests;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\MatchDecision;
use PHPUnit\Framework\Assert;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Foundation\Testing\TestResponse;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication;

    protected $authorizedUser;
    protected $unauthorizedUser;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://ringside.app';

    protected function setUp()
    {
        parent::setUp();

        TestResponse::macro('data', function ($key) {
            return $this->original->getData()[$key];
        });

        EloquentCollection::macro('assertContains', function ($value) {
            Assert::assertTrue($this->contains($value), 'Failed asserting that the collection contains the specified value.');
        });

        EloquentCollection::macro('assertNotContains', function ($value) {
            Assert::assertFalse($this->contains($value), 'Failed asserting that the collection does not contain the specified value.');
        });

        EloquentCollection::macro('assertEquals', function ($items) {
            Assert::assertEquals(count($this), count($items));
            $this->zip($items)->each(function ($pair) {
                list($a, $b) = $pair;
                Assert::assertTrue($a->is($b));
            });
        });

        $this->setupUnauthorizedUser();
        $this->setupMatchDecisions();
    }

    /**
     * Creates an authorized user of the included permissions.
     *
     * @param array $slugs
     * @return void
     */
    protected function setupAuthorizedUser($slugs)
    {
        $this->authorizedUser = factory(User::class)->create();
        $role = factory(Role::class)->create();

        if (is_array($slugs)) {
            foreach ($slugs as $slug) {
                $permission = factory(Permission::class)->create(['slug' => $slug]);
                $role->givePermissionTo($permission);
            }
        } else {
            $permission = factory(Permission::class)->create(['slug' => $slugs]);
            $role->givePermissionTo($permission);
        }

        $this->authorizedUser->assignRole($role);
    }

    protected function setupUnauthorizedUser()
    {
        $this->unauthorizedUser = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $this->unauthorizedUser->assignRole($role);
    }

    protected function setupMatchDecisions()
    {
        factory(MatchDecision::class)->create(['id' => 1, 'name' => 'Pinfall', 'slug' => 'pinfall']);
        factory(MatchDecision::class)->create(['id' => 2, 'name' => 'Submission', 'slug' => 'submission']);
        factory(MatchDecision::class)->create(['id' => 3, 'name' => 'Disqualification', 'slug' => 'dq']);
        factory(MatchDecision::class)->create(['id' => 4, 'name' => 'Countout', 'slug' => 'countout']);
        factory(MatchDecision::class)->create(['id' => 5, 'name' => 'Knockout', 'slug' => 'knockout']);
        factory(MatchDecision::class)->create(['id' => 6, 'name' => 'Stipulation', 'slug' => 'stipulation']);
        factory(MatchDecision::class)->create(['id' => 7, 'name' => 'Forfeit', 'slug' => 'forfeit']);
        factory(MatchDecision::class)->create(['id' => 8, 'name' => 'Time Limit Draw', 'slug' => 'draw']);
        factory(MatchDecision::class)->create(['id' => 9, 'name' => 'No Decision', 'slug' => 'nodecision']);
        factory(MatchDecision::class)->create(['id' => 10, 'name' => 'Reversed Decision', 'slug' => 'revdecision']);
    }

    protected function dumpSessionErrors()
    {
        dd(app('session.store')->get('errors')->getBag('default'));
    }
}
