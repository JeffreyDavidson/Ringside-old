<?php

namespace App\Repositories;

use DB;
use App\Models\Title;
use App\Models\Wrestler;
use App\Models\Championship;

class TitleRecordsRepository
{
    /**
     * Gets champions with the most title defenses.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function mostTitleDefenses(Title $title)
    {
        $maxDefenses = Championship::selectRaw('MAX(successful_defenses) AS max')->value('max');

        return $title->champions()->wherePivot('successful_defenses', $maxDefenses)->get();
    }

    /**
     * Gets the champions with the most title reigns.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Support\Collection
     */
    public function mostTitleReigns(Title $title)
    {
        $maxReigns = Wrestler::query()
                ->groupBy('championships.wrestler_id')
                ->join('championships', 'wrestlers.id', '=', 'championships.wrestler_id')
                ->selectRaw('COUNT(championships.wrestler_id) AS reigns')
                ->value('reigns');

        return Wrestler::query()
                ->selectRaw('wrestlers.*, COUNT(championships.wrestler_id) AS reigns')
                ->groupBy('championships.wrestler_id')
                ->join('championships', 'wrestlers.id', '=', 'championships.wrestler_id')
                ->havingRaw('reigns = ?', [$maxReigns])
                ->get();
    }

    /**
     * Gets the champions with the longest title reign.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function longestTitleReigns(Title $title)
    {
        $now = \Carbon\Carbon::now()->toDateTimeString();

        $reign = DB::table('championships')
            ->selectRaw('MAX(DATEDIFF(IFNULL(lost_on, ?), won_on)) AS diff', [$now])
            ->value('diff');

        return Wrestler::whereHas('titles', function ($query) use ($now, $reign) {
            $query->whereRaw('DATEDIFF(IFNULL(championships.lost_on, ?), championships.won_on) = ?', [
                $now, $reign,
            ]);
        })->get();
    }
}
