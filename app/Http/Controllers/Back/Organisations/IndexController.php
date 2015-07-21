<?php

namespace App\Http\Controllers\Back\Organisations;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Organisation\Organisation;

class IndexController extends BaseController
{
    /**
     * Shows the home view.
     *
     * @param string $organisation
     *
     * @return \Illuminate\View\View
     */
    public function get($organisation)
    {
        return view('back.organisation.get', [
            'organisation' => ($organisation = $this->getOrganisation($organisation)),
            'repositories' => $this->getRepositories($organisation),
        ]);
    }

    /**
     * Get the organisation.
     *
     * @param string $organisation
     *
     * @return \App\Models\Organisation\Organisation
     */
    protected function getOrganisation($organisation)
    {
        $organisation = auth()->user()->organisations()
            ->where('name', $organisation)
            ->first();

        if (!$organisation) {
            throw new NotFoundHttpException();
        }

        return $organisation;
    }

    /**
     * Get all the organisation's repositories.
     *
     * @param \App\Models\Organisation\Organisation $organisation
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRepositories(Organisation $organisation)
    {
        return $organisation->repositories;
    }
}
