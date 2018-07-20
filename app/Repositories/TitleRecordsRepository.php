<?php

namespace App\Repositories;

use App\Models\Title;
use App\Models\Wrestler;
use App\Models\Champion;

class TitleRecordsRepository
{
    public function mostTitleDefenses(Title $title)
    {
        $maxDefenses = Champion::selectRaw('MAX(successful_defenses) AS max')->value('max');

        return Champion::with('wrestler')
            ->where('title_id', $title->id)
            ->where('successful_defenses', $maxDefenses)
            ->get();
    }

    public function mostTitleReigns(Title $title)
    {
        $max = Wrestler::query()
                ->groupBy('champions.wrestler_id')
                ->join('champions', 'wrestlers.id', '=', 'champions.wrestler_id')
                ->selectRaw('COUNT(champions.wrestler_id) AS reigns')
                ->value('reigns');

        return Wrestler::query()
                ->selectRaw('wrestlers.*, COUNT(champions.wrestler_id) AS reigns')
                ->groupBy('champions.wrestler_id')
                ->join('champions', 'wrestlers.id', '=', 'champions.wrestler_id')
                ->havingRaw("reigns = ?", [$max])
                ->get();
    }

    public function longestTitleReign(Title $title)
    {
        $maxDateDiff = Champion::selectRaw('MAX(DATEDIFF(IFNULL(lost_on, NOW()), won_on)) AS diff')->value('diff');

        return Champion::with('wrestler')
            ->select('champions.lost_on', 'champions.won_on', 'wrestler_id')
            ->where('title_id', $title->id)
            ->whereRaw("DATEDIFF(IFNULL(lost_on, NOW()), won_on) = {$maxDateDiff}")
            ->get();
    }
}
