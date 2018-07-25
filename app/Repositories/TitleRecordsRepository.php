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
        // $maxReigns = Wrestler::query()
        //         ->groupBy('championships.wrestler_id')
        //         ->join('championships', 'wrestlers.id', '=', 'championships.wrestler_id')
        //         ->selectRaw('COUNT(championships.wrestler_id) AS reigns')
        //         ->value('reigns');
        // $maxReigns = Championship::query()
        //                     ->withCount('wrestler_id')
        //                     ->orderBy('wrestler_id_count', 'desc')
        //                     ->value('wrestler_id_count');
        $maxReigns = Championship::query()
        ->withCount('wrestlers')
    ->orderBy('wrestler_count', 'desc')
    ->select('wrestler_count');

        return Wrestler::query()
                ->selectRaw('wrestlers.*, COUNT(championships.wrestler_id) AS reigns')
                ->groupBy('championships.wrestler_id')
                ->join('championships', 'wrestlers.id', '=', 'championships.wrestler_id')
                ->havingRaw('reigns = ?', [$maxReigns])
                ->get();
    }

    public function longestTitleReign(Title $title)
    {
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $maxDateDiff = Championship::selectRaw('MAX(DATEDIFF(IFNULL(lost_on, ?), won_on)) AS diff', [$now])->value('diff');

        return Championship::with('wrestler')
            ->select('championships.lost_on', 'championships.won_on', 'wrestler_id')
            ->where('title_id', $title->id)
            ->whereRaw('DATEDIFF(IFNULL(lost_on, ?), won_on) = ?', [$now, $maxDateDiff])
            ->get();
    }
}
