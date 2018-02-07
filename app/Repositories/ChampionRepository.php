<?php

namespace App\Repositories;

use App\Models\Title;
use App\Models\Champion;

class ChampionRepository {

    public function mostTitleDefences() 
    {
        return Champion::with('wrestler')
            ->selectRaw('COUNT(*) as count, wrestler_id')
            ->where('title_id', $title->id)
            ->orderBy('count', 'desc')
            ->groupBy('wrestler_id')
            ->first();
    }

    public function mostTitleReigns(Title $title) 
    {
        return Champion::with('wrestler')
            ->selectRaw('COUNT(*) as count, wrestler_id')
            ->where('title_id', $title->id)
            ->orderBy('count', 'desc')
            ->groupBy('wrestler_id')
            ->first();
    }

    public function longestTitleReign() 
    {
        return Champion::with('wrestler')
            ->select(DB::raw('DATEDIFF(IFNULL(DATE(champions.lost_on), NOW()), DATE(champions.won_on)) as length, wrestler_id'))
            ->where('title_id', $title->id)
            ->orderBy('length', 'desc')
            ->groupBy('length', 'wrestler_id')
            ->first();
    }
}