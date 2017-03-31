<?php

namespace App\Http\Controllers;

use App\Wrestler;
use Illuminate\Http\Request;

class InjuredWrestlersController extends Controller
{
    public function index()
    {
        $wrestlers = Wrestler::injured()->get();

        return view('wrestlers.injured', ['wrestlers' => $wrestlers]);
    }
}
