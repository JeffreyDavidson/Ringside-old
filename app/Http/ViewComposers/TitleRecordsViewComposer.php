<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Championship;
use App\Repositories\TitleRecordsRepository;

class TitleRecordsViewComposer
{
    /**
     * The title records repository implementation.
     *
     * @var \App\Repositories\TitleRecordsRepository
     */
    protected $repository;

    /**
     * Create a new profile composer.
     *
     * @param  \App\Repositories\TitleRecordsRepository  $repository
     * @return void
     */
    public function __construct(TitleRecordsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        /** @var \App\Models\Title $title */
        $title = $view->getData()['title'];

        $longestTitleReigns = $this->repository->longestTitleReigns($title)->map(function (Championship $reign) {
            return [
                'wrestler' => $reign->wrestler->name,
                'length' => number_format($reign->length) . ' ' . str_plural('day', $reign->length),
            ];
        });

        $mostTitleDefenses = $this->repository->mostTitleDefenses($title)->map(function (Championship $reign) {
            return [
                'wrestler' => $reign->wrestler->name,
                'count' => $reign->successful_defenses,
            ];
        });

        $mostTitleReigns = $this->repository->mostTitleReigns($title)->map(function (Championship $reign) {
            return [
                'wrestler' => $reign->wrestler->name,
                'count' => $reign->count,
            ];
        });

        $view->with(compact('longestTitleReigns', 'mostTitleDefenses', 'mostTitleReigns'));
    }
}
