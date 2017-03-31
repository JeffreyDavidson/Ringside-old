<?php

namespace App\Http\Controllers;

use App\Wrestler;
use Illuminate\Http\Request;

class RetiredWrestlersController extends Controller
{
    public function index()
    {
        $wrestlers = Wrestler::retired()->get();

        return view('wrestlers.retired', ['wrestlers' => $wrestlers]);
    }
}
