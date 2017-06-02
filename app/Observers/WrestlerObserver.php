<?php

namespace App\Observers;

use App\Models\Wrestler;

class WrestlerObserver
{
    /**
     * Listen to the Wrestler created event.
     *
     * @param  Wrestler  $wrestler
     * @return void
     */
    public function created(Wrestler $wrestler)
    {
        $wrestler->bio()->create([
            'hometown' => request('hometown'),
            'height' => (request('feet', 0) * 12) + request('inches', 0),
            'weight' => request('weight'),
            'signature_move' => request('signature_move'),
        ]);
    }

    /**
     * Listen to the Wrestler created event.
     *
     * @param  Wrestler  $wrestler
     * @return void
     */
    public function updated(Wrestler $wrestler)
    {
        $wrestler->bio()->update([
            'hometown' => request('hometown'),
            'height' => (request('feet', 0) * 12) + request('inches', 0),
            'weight' => request('weight'),
            'signature_move' => request('signature_move'),
        ]);
    }
}