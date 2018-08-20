<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\TitleRecordsRepository;

class TitleRecordsViewComposer
{
    /**
     * The title records repository implementation.
     *
     * @var TitleRecordsRepository
     */
    protected $repository;

    /**
     * Create a new profile composer.
     *
     * @param  TitleRecordsRepository  $users
     * @return void
     */
    public function __construct(TitleRecordsRepository $repository)
    {
        $this->repository = $repository;
    }

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
                'length' => number_format($reign->length).' '.str_plural('day', $reign->length),
            ];
        });

        dd($longestTitleReigns);

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
