<?php

namespace App\Repositories;

use App\Models\Title;
use App\Models\Wrestler;
use App\Models\Championship;

class TitleRecordsRepository
{
    public function mostTitleDefenses(Title $title)
    {
        $maxDefenses = Championship::selectRaw('MAX(successful_defenses) AS max')->value('max');

        return Championship::with('wrestler')
            ->where('title_id', $title->id)
            ->where('successful_defenses', $maxDefenses)
            ->get();
    }

    public function mostTitleReigns(Title $title)
    {
        $max = Wrestler::query()
                ->groupBy('championships.wrestler_id')
                ->join('championships', 'wrestlers.id', '=', 'championships.wrestler_id')
                ->selectRaw('COUNT(championships.wrestler_id) AS reigns')
                ->value('reigns');

        return Wrestler::query()
                ->selectRaw('wrestlers.*, COUNT(championships.wrestler_id) AS reigns')
                ->groupBy('championships.wrestler_id')
                ->join('championships', 'wrestlers.id', '=', 'championships.wrestler_id')
                ->havingRaw('reigns = ?', [$max])
                ->get();
    }

    public function longestTitleReign(Title $title)
    {
        $maxDateDiff = Championship::selectRaw('MAX(DATEDIFF(IFNULL(lost_on, NOW()), won_on)) AS diff')->value('diff');

        return Championship::with('wrestler')
            ->select('championships.lost_on', 'championships.won_on', 'wrestler_id')
            ->where('title_id', $title->id)
            ->whereRaw("DATEDIFF(IFNULL(lost_on, NOW()), won_on) = {$maxDateDiff}")
            ->get();
    }
}
