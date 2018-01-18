<?php

namespace App\Http\Middleware;

use App\Policies\ApplicationPolicy;
use Closure;

class Authenticate
{
    /**
     * @var ApplicationPolicy
     */
    private $policy;

    /**
     * Create a new filter instance.
     *
     * @param ApplicationPolicy $policy
     */
    public function __construct(ApplicationPolicy $policy)
    {
        $this->policy = $policy;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->policy->init();

        return $next($request);
    }
}
