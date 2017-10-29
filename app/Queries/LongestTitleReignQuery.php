<?php

namespace App\Queries;

use App\Models\Champion;
use App\Models\Title;
use Illuminate\Support\Facades\DB;

class LongestTitleReignQuery
{
    /**
     * Returns collection of champions by length of reign.
     *
     * @param $title
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function get(Title $title)
    {
        return Champion::with('wrestler')
            ->select(DB::raw("DATEDIFF(IFNULL(DATE(champions.lost_on), NOW()), DATE(champions.won_on)) as length, wrestler_id"))
            ->where('title_id', $title->id)
            ->orderBy('length', 'desc')
            ->groupBy('length', 'wrestler_id')
            ->limit(1)
            ->get();
    }
}