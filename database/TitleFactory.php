<?php

use App\Models\Title;

class TitleFactory
{
    public static function createReignForWrestlerBetweenDates($wrestler, $start, $end = NULL)
    {
        $title = factory(Title::class)->create(['introduced_at' => $start->subWeeks(2)]);

        $wrestler->winTitle($title, $start);

        if (!is_null($end)) {
            $wrestler->loseTitle($title, $end);
        }

        return $title;
    }
}
