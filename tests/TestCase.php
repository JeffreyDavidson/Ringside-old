<?php

namespace Tests;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\WrestlerStatus;
use PHPUnit\Framework\Assert;
use Illuminate\Database\Eloquent\Collection;
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

        Collection::macro('assertContains', function ($value) {
            Assert::assertTrue($this->contains($value), 'Failed asserting that the collection contains the specified value.');
        });

        Collection::macro('assertNotContains', function ($value) {
            Assert::assertFalse($this->contains($value), 'Failed asserting that the collection does not contain the specified value.');
        });

        Collection::macro('assertEquals', function ($items) {
            Assert::assertEquals(count($this), count($items));
            $this->zip($items)->each(function ($pair) {
                list($a, $b) = $pair;
                Assert::assertTrue($a->is($b));
            });
        });

        $this->setupUnauthorizedUser();
        $this->setupWrestlerStatuses();
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

    protected function setupWrestlerStatuses()
    {
        factory(WrestlerStatus::class)->create(['id' => 1, 'name' => 'Active']);
        factory(WrestlerStatus::class)->create(['id' => 2, 'name' => 'Inactive']);
        factory(WrestlerStatus::class)->create(['id' => 3, 'name' => 'Injured']);
        factory(WrestlerStatus::class)->create(['id' => 4, 'name' => 'Suspended']);
        factory(WrestlerStatus::class)->create(['id' => 5, 'name' => 'Retired']);
    }
}
