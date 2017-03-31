<?php

namespace App\Http\Controllers;

use App\Wrestler;
use Illuminate\Http\Request;

class ActiveWrestlersController extends Controller
{
    public function index()
    {
        $wrestlers = Wrestler::active()->get();

        return view('wrestlers.active', ['wrestlers' => $wrestlers]);
    }
}
