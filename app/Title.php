<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Title extends Model
{

    public function titleHistory()
    {
        return $this->hasMany(TitleHistory::class)->orderBy('won_on', 'desc');
    }
    public function currentRecord()
    {
        return $this->hasOne(TitleHistory::class)->orderBy('won_on', 'desc');
    }
    public function currentHolder()
    {
        return $this->currentRecord() ? $this->currentRecord->wrestler: null;
    }

    public function winTitle($wrestler)
    {
        return $this->titleHistory()->create(['wrestler_id' => $wrestler->id, 'won_on' => Carbon::now()]);
    }
}
