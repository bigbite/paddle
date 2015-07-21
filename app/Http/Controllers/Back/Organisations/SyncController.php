<?php

namespace App\Http\Controllers\Back\Organisations;

use App\Http\Controllers\Controller as BaseController;
use App\Models\User\User;
use App\Models\Organisation\Organisation;
use App\Services\GitHub\OrganisationRepositories as Repositories;

class SyncController extends BaseController
{
    /**
     * Constructs the SyncController.
     *
     * @param \App\Services\GitHub\OrganisationRepositories $repositories
     */
    public function __construct(Repositories $repositories)
    {
        $this->repositories = $repositories;
    }

    /**
     * Attempts to sync the repositories from GitHub.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function get($organisation)
    {
        $user = auth()->user();
        $repositories = $this->getRepositories($user, $organisation = $this->getOrganisation($user, $organisation));

        if ($repositories !== null) {
            $this->repositories->sync($organisation, $repositories);

            $this->flash(
                'Successfully synced '.number_format($repositories->count()).' repositories!',
                BaseController::FLASH_SUCCESS
            );
        }

        return redirect()->back();
    }

    /**
     * Attempts to get the models for an organisation.
     *
     * @param \App\Models\User\User                 $user
     * @param \App\Models\Organisation\Organisation $organisation
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getRepositories(User $user, Organisation $organisation)
    {
        $repositories = $this->repositories->lists($user, $organisation);

        if ($repositories === null) {
            $this->flash(
                'Something went wrong when requesting the repositories, try logging out and back in.',
                BaseController::FLASH_ERROR
            );
        }

        return $repositories;
    }

    /**
     * Get the organisation.
     *
     * @param \App\Models\User\User $user
     * @param string                $organisation
     *
     * @return \App\Models\Organisation\Organisation
     */
    protected function getOrganisation(User $user, $organisation)
    {
        $organisation = $user->organisations()
            ->where('name', $organisation)
            ->first();

        if (!$organisation) {
            throw new NotFoundHttpException();
        }

        return $organisation;
    }
}
