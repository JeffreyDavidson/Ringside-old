<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $request;

    protected $authorizeResource = null;

    public function __construct(Request $request)
    {
        $this->request = $request;
        if ($this->authorizeResource) {
            $this->authorizeResource(...(array) $this->authorizeResource);
        }
    }

    protected function resourceAbilityMap()
    {
        return [
            'index' => 'index',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
        ];
    }
}
