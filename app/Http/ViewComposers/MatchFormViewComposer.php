<?php

namespace App\Http\ViewComposers;

use App\Repositories\TitleRecordsRepository;
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
        /** @var \App\Models\Title $title */
        $title = $view->title;

        $longestTitleReigns = $this->repository->longestTitleReigns($title)->map(function (Champion $reign) {
            return [
                'wrestler' => $reign->wrestler->name,
                'length' => number_format($reign->length) . ' ' . str_plural('day', $reign->length),
            ];
        });

        $mostTitleDefenses = $this->repository->mostTitleDefenses($title)->map(function (Champion $reign) {
            return [
                'wrestler' => $reign->wrestler->name,
                'count' => $reign->successful_defenses,
            ];
        });

        $mostTitleReigns = $this->repository->mostTitleReigns($title)->map(function (Champion $reign) {
            return [
                'wrestler' => $reign->wrestler->name,
                'count' => $reign->count,
            ];
        });

        $view->with(compact('longestTitleReigns', 'mostTitleDefenses', 'mostTitleReigns'));
    }
}
