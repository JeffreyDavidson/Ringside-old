<?php

namespace App\Http\ViewComposers;

use App\Models\MatchType;
use App\Models\Referee;
use App\Models\Stipulation;
use App\Models\Title;
use App\Models\Wrestler;
use Illuminate\View\View;

class MatchFormViewComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        /** @var \App\Models\Event $event */
        $event = request()->event;

        $matchTypes = MatchType::orderby('id')->pluck('name', 'id');
        $stipulations = Stipulation::orderby('id')->pluck('name', 'id');
        $titles = Title::active()->orderby('id')->pluck('name', 'id');
        $referees = Referee::hiredBefore($event->date)->active()->orderby('id')->get()->pluck('name', 'id');
        $wrestlers = Wrestler::hiredBefore($event->date)->active()->orderby('id')->pluck('name', 'id');

        $view->with(compact('matchTypes', 'stipulations', 'titles', 'referees', 'wrestlers'));
    }
}
