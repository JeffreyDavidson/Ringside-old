<?php

namespace App\Repositories;

use App\Models\Title;
use App\Models\Champion;
use Carbon\Carbon;

class ChampionRepository
{
    public static function mostTitleDefenses(Title $title)
    {
        return Champion::with('wrestler')
            ->where('title_id', $title->id)
            ->orderBy('successful_defenses', 'desc')
            ->first();
    }

    public static function mostTitleReigns(Title $title)
    {
        return Champion::with('wrestler')
            ->selectRaw('COUNT(*) as count, wrestler_id')
            ->where('title_id', $title->id)
            ->orderBy('count', 'desc')
            ->groupBy('wrestler_id')
            ->first();
    }

    public static function longestTitleReign(Title $title)
    {
        $champions = Champion::with('wrestler')
            ->select('champions.lost_on', 'champions.won_on', 'wrestler_id')
            ->where('title_id', $title->id)
            ->orderBy('length', 'desc')
            ->groupBy('length', 'wrestler_id')
            ->get();

        return $champions->sortByDesc(function ($champion) {
            return $champion->timeSpentAsChampion();
        })->first();
    }
}
