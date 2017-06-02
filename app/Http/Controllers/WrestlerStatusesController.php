<?php

namespace App\Http\Controllers;

use App\Models\WrestlerStatus;
use Illuminate\Http\Request;

class WrestlerStatusesController extends Controller
{
	public function index()
	{
		WrestlerStatus::all();
	}
}