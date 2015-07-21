<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller as BaseController;
use App\Services\GitHub\Repositories;
use App\Services\GitHub\Organisations;
use App\Models\User\User;

class SyncController extends BaseController
{
    /**
     * The GitHub repositories service.
     *
     * @var \App\Services\GitHub\Repositories
     */
    protected $repositories;

    /**
     * The GitHub organisations service.
     *
     * @var \App\Services\GitHub\Organisations
     */
    protected $organisations;

    /**
     * Constructs the SyncController.
     *
     * @param \App\Services\GitHub\Repositories  $repositories
     * @param \App\Services\GitHub\Organisations $organisations
     */
    public function __construct(Repositories $repositories, Organisations $organisations)
    {
        $this->repositories = $repositories;
        $this->organisations = $organisations;
    }

    /**
     * Attempts to sync the repositories from GitHub.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function get()
    {
        $repositories = $this->getRepositories($user = auth()->user());

        if ($repositories !== null) {
            $this->repositories->sync($user, $repositories);

            $this->flash(
                'Successfully synced '.number_format($repositories->count()).' repositories!',
                BaseController::FLASH_SUCCESS
            );
        }

        $organisations = $this->getOrganisations($user = auth()->user());

        if ($organisations !== null) {
            $this->organisations->sync($user, $organisations);

            $this->flash(
                'Successfully synced '.number_format($organisations->count()).' organisations!',
                BaseController::FLASH_SUCCESS
            );
        }

        return redirect()->back();
    }

    /**
     * Attempts to get the models for a user.
     *
     * @param \App\Models\User\User $user
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getRepositories(User $user)
    {
        $repositories = $this->repositories->lists($user);

        if ($repositories === null) {
            $this->flash(
                'Something went wrong when requesting the repositories, try logging out and back in.',
                BaseController::FLASH_ERROR
            );
        }

        return $repositories;
    }

    /**
     * Attempts to get the models for a user.
     *
     * @param \App\Models\User\User $user
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getOrganisations(User $user)
    {
        $organisations = $this->organisations->lists($user);

        if ($organisations === null) {
            $this->flash(
                'Something went wrong when requesting the organisations, try logging out and back in.',
                BaseController::FLASH_ERROR
            );
        }

        return $organisations;
    }
}
