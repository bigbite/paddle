<?php

namespace App\Http\Controllers\Back\Repositories;

use App\Http\Controllers\Controller as BaseController;
use App\Services\GitHub\Releases;
use App\Models\User\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Events\RepositoryWasReleased;

class ReleaseController extends BaseController
{
    /**
     * The GitHub releases service.
     *
     * @var \App\Services\GitHub\Releases
     */
    protected $releases;

    /**
     * Constructs the SyncController.
     *
     * @param \App\Services\GitHub\Releases $releases
     */
    public function __construct(Releases $releases)
    {
        $this->releases = $releases;
    }

    /**
     * Releases a version.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $repository
     * @param string                   $release
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function release(Request $request, $repository, $release = null)
    {
        $release = trim(ltrim($release ?: $request->input('release'), '/'));

        if (!preg_match('~^[^/]+$~', $release) && strlen($release) !== 0) {
            $this->flash(
                'Invalid release "'.$release.'"!',
                BaseController::FLASH_ERROR
            );

            return redirect()->route('get::back.repository', compact('repository'));
        }

        $repository = $this->getRepository($repository);

        if (!$repository->rigged) {
            $this->flash(
                'This repository has not yet been set up, please complete the "Details" section!',
                BaseController::FLASH_ERROR
            );

            return redirect()->route('get::back.repository', compact('repository'));
        }

        $release = strlen($release) === 0 ? env('GIT_BRANCH') : $release;

        event(new RepositoryWasReleased(
            $repository,
            $release
        ));

        $this->flash(
            'A deploy for '.$release.' has been queued!',
            BaseController::FLASH_SUCCESS
        );

        return redirect()->route('get::back.repository', compact('repository'));
    }

    /**
     * Get an authorized user's repository.
     *
     * @param string $repository
     *
     * @return \App\Models\Repository\Repository
     */
    protected function getRepository($repository)
    {
        list($vendor, $package) = explode('/', $repository, 2);

        $user = auth()->user();
        $query = $user->repositories();

        if ($vendor !== strtolower($user->name)) {
            $organisation = $user->organisations()
                ->where('name', $vendor)
                ->first();

            if (!$organisation) {
                throw new NotFoundHttpException();
            }

            $query = $organisation->repositories();
        }

        $repository = $query->where('name', $package)->first();

        if (!$repository) {
            throw new NotFoundHttpException();
        }

        return $repository;
    }
}
