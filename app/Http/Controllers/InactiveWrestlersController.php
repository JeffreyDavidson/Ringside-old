<?php

namespace App\Http\Controllers;

use App\Wrestler;
use Illuminate\Http\Request;

class InactiveWrestlersController extends Controller
{
    public function index()
    {
        $wrestlers = Wrestler::inactive()->get();

        return view('wrestlers.inactive', ['wrestlers' => $wrestlers]);
    }
}
