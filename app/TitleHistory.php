<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TitleHistory extends Model
{
    protected $table = 'title_wrestler';
    protected $dates = ['won_on'];
    protected $guarded = ['id'];

    public function title()
    {
        return $this->belongsTo(Title::class);
    }

    public function wrestler()
    {
        return $this->belongsTo(Wrestler::class);
    }
}
