<?php

use App\Models\Manager;

class ManagerFactory
{
    public static function createHiredTimeForWrestlerBetweenDates($wrestler, $start, $end)
    {
        $manager = factory(Manager::class)->create(['hired_at' => $start->subWeeks(2)]);

        $wrestler->hireManager($manager, $start);

        if (!is_null($end)) {
            $wrestler->fireManager($manager, $end);
        }

        return $manager;
    }
}
