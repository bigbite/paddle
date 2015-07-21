<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller as BaseController;

class HomeController extends BaseController
{
    /**
     * Shows the home view.
     *
     * @return \Illuminate\View\View
     */
    public function get()
    {
        return view('back.get', [
            'organisations' => $this->getOrganisations(),
            'repositories' => $this->getRepositories(),
        ]);
    }

    /**
     * Get all the authorized user's organisations.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getOrganisations()
    {
        return auth()->user()->organisations()
            ->with('repositories')->get();
    }

    /**
     * Get all the authorized user's repositories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRepositories()
    {
        return auth()->user()->repositories;
    }
}
