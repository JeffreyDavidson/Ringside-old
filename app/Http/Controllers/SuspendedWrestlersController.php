<?php

namespace App\Http\Controllers;

use App\Wrestler;
use Illuminate\Http\Request;

class SuspendedWrestlersController extends Controller
{
    public function index()
    {
        $wrestlers = Wrestler::suspended()->get();

        return view('wrestlers.suspended', ['wrestlers' => $wrestlers]);
    }
}
