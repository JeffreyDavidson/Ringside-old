<?php

namespace App\Policies;

use App\Models\Permission;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPolicy
{
    use HandlesAuthorization;

    /**
     * @var GateContract
     */
    private $gate;

    /**
     * Create a new policy instance.
     *
     * @param \Illuminate\Contracts\Auth\Access\Gate  $gate
     */
    public function __construct(GateContract $gate)
    {
        $this->gate = $gate;
    }

    public function init()
    {
//        dd($this->getPermissions());
        foreach($this->getPermissions() as $permission) {
            $this->gate->define($permission->slug, function ($user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }
    }

    /**
     * Fetch the collection of site permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPermissions()
    {
        return Permission::with('roles')->get();
    }
}
