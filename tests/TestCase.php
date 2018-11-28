<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use PHPUnit\Framework\Assert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

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

        // $testName = str_replace(["test", "_"], ["", " "], $this->getName());
        // $testName = preg_replace_callback("/[a-zA-Z0-9]{3,}\b/", function($match){
        //     return ucfirst($match[0]);
        // }, $testName);

        // dump(" ->" . $testName);
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

    protected function dumpSessionErrors()
    {
        dd(app('session.store')->get('errors')->getBag('default'));
    }

    public function assertNotSoftDeleted(Model $model)
    {
        return $this->assertDatabaseHas($model->getTable(), [
            $model->getKeyName() => $model->getKey(),
            $model->getDeletedAtColumn() => null,
        ]);
    }

    public function assertValuesEqual(array $expected, array $actual, $msg = '')
    {
        return $this->assertEquals(array_values($expected), array_values($actual), $msg);
    }
}
