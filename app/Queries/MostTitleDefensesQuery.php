<?php

namespace App\Queries;

use App\Models\Title;
use App\Models\Champion;

class MostTitleDefensesQuery
{
    /**
     * Returns collection of champions by most title defenses.
     *
     * @param \App\Models\Title $title
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function get(Title $title)
    {
        return Champion::with('wrestler')
            //->join('match_title', 'title_wrestler.title_id', '=', 'match_title.title_id')
            ->selectRaw('COUNT(*) as count, wrestler_id')
            ->where('title_id', $title->id)
            ->orderBy('count', 'desc')
            ->groupBy('wrestler_id')
            ->limit(1)
            ->get();
    }
}
