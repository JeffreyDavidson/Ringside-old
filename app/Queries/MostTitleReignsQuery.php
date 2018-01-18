<?php

namespace App\Queries;

use App\Models\Champion;
use App\Models\Title;

class MostTitleReignsQuery
{
    /**
     * Returns collection of champions by most title reigns.
     *
     * @param \App\Models\Title $title
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function get(Title $title)
    {
        return Champion::with('wrestler')
            ->selectRaw('COUNT(*) as count, wrestler_id')
            ->where('title_id', $title->id)
            ->orderBy('count', 'desc')
            ->groupBy('wrestler_id')
            ->limit(1)
            ->get();
    }
}
