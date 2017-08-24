<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laracodes\Presenter\Traits\Presentable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Title extends Model
{
    use Presentable, SoftDeletes;

    protected $presenter = 'App\Presenters\TitlePresenter';

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['introduced_at', 'retired_at'];

    /**
     * A title can have many champions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function champions()
    {
        return $this->hasMany(TitleHistory::class);
    }

    /**
     * A title can be added to many matches.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function matches()
    {
        return $this->belongsToMany(Match::class)->with('event');
    }

    public function firstMatchDate()
    {
        return $this->matches->first()->date;
    }

    /**
     * Retrieve valid titles to be competed for.
     *
     * @param Builder $query
     * @param $date
     */
    public function scopeValid($query, $date)
    {
        return $query->where('introduced_at', '<=', $date->toDateString())->where(function($query) use ($date) {
			$query->whereNull('retired_at')->orWhere('retired_at', '>', $date->toDateString());
		});
    }

    /**
     * Crown the new champion.
     *
     * @param $wrestler
     * @param $date
     */
    public function setNewChampion($wrestler, $date = null)
	{
		if(! $date) {
			$date = Carbon::now();
		}

    	    if($formerChampion = $this->getCurrentChampion()) {
			$formerChampion->loseTitle($this, $date);
		}

		$wrestler->winTitle($this, $date);
    }

    /**
     * Get the longest title reign held by a wrestler.
     *
     * @return Wrestler $wrestler
     */
    public function longest_title_reign()
    {
        $wrestlers = $this->longest_title_reign_query();

        $longest = $this->longest_title_reign_query()->first()->length;

        return $wrestlers->where('length', $longest);

        //return $wrestlers->filter(function($item) use ($longest) {
        //    return $item->length == $longest;
        //});
    }

    public function longest_title_reign_query()
    {
        return $this->champions()
            ->join('wrestlers', 'wrestlers.id', '=', 'title_wrestler.wrestler_id')
            ->selectRaw("DATEDIFF(IFNULL(DATE(title_wrestler.lost_on), NOW()), DATE(title_wrestler.won_on)) as length")
            ->addSelect('wrestlers.name')
            ->orderBy('length', 'desc')
            ->get();
    }

    /**
     * Get the wrestler who has defended the title the most times.
     *
     * @return Wrestler $wrestler
     */
    public function most_title_defenses()
    {
        $wrestlers = $this->most_title_defences_query();

        $most = $wrestlers->first()->count;

        return $wrestlers->where('count', $most);

        //return $wrestlers->filter(function($item) use($most) {
        //    return $item->count == $most;
        //});
    }

    public function most_title_defences_query()
    {
        return $this->champions()
            ->join('wrestlers', 'wrestlers.id', '=', 'title_wrestler.wrestler_id')
            ->join('match_title', 'title_wrestler.title_id', '=', 'match_title.title_id')
            ->selectRaw('COUNT(*) as count')
            ->addSelect('wrestlers.name')
            ->groupBy('wrestler_id')->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Get the wrestler who has held the title the most times.
     *
     * @return Wrestler $wrestler
     */
    public function most_title_reigns()
    {
        $wrestlers = $this->most_title_defences_query();

        $most = $wrestlers->first()->count;

        return $wrestlers->where('count', $most);

        //return $wrestlers->filter(function($item) use($most) {
        //    return $item->count == $most;
        //});
    }

    public function most_title_reigns_query()
    {
        return $this->champions()
            ->join('wrestlers', 'wrestlers.id', '=', 'title_wrestler.wrestler_id')
            ->selectRaw('COUNT(*) as count')
            ->addSelect('wrestlers.name')
            ->groupBy('wrestler_id')->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Get the current champion for the title.
     *
     * @return Wrestler $wrestler|null
     */
	public function getCurrentChampion() {
		return $this->champions()
            ->whereNull('lost_on')
            ->first() ? $this->champions()->whereNull('lost_on')->first()->wrestler : null;
    }

    public function hasMatches()
    {
        return $this->matches->isNotEmpty();
    }
}
