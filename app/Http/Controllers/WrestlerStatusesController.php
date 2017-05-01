<?php

namespace App\Http\Controllers;

use App\Models\WrestlerStatus;
use Illuminate\Http\Request;

class WrestlerStatusesController extends Controller
{
	public function index()
	{
		$statuses = WrestlerStatus::all();

		if ($this->wantsJson() || $this->ajax()) {
			return response()->json($statuses);
		}
	}
}