<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Title extends Model
{
    public function titleHistory()
    {
        return $this->hasMany(TitleHistory::class)->orderBy('won_on', 'desc');
    }

    public function wrestler()
    {
        return $this->belongsTo(Wrestler::class);
    }

    public function winTitle($wrestler)
    {
        return DB::transaction(function () use ($wrestler) {
            $this->titleHistory()->create(['wrestler_id' => $wrestler->id, 'won_on' => Carbon::now()]);
            $this->wrestler_id = $wrestler->id;
            $this->save();
            return $this;
        });
    }
}
