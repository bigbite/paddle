<?php

namespace App\Http\Controllers\Back\Repositories;

use App\Http\Controllers\Controller as BaseController;
use App\Services\GitHub\Webhooks;
use App\Models\User\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HookController extends BaseController
{
    /**
     * The GitHub webhooks service.
     *
     * @var \App\Services\GitHub\Webhooks
     */
    protected $webhooks;

    /**
     * Constructs the SyncController.
     *
     * @param \App\Services\GitHub\Webhooks $webhooks
     */
    public function __construct(Webhooks $webhooks)
    {
        $this->webhooks = $webhooks;
    }

    /**
     * Hooks a repository.
     *
     * @param string $repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hook($repository)
    {
        $repository = $this->getRepository($repository);

        if (!$this->webhooks->apply(auth()->user(), $repository)) {
            $this->flash(
                'Failed to apply webhook, try relogging?',
                BaseController::FLASH_ERROR
            );
        } else {
            $this->flash(
                'Successfully applied webhook, '.$repository->getRouteKey().' will be synced when new commits are made on GitHub!',
                BaseController::FLASH_SUCCESS
            );
        }


        return redirect()->route('get::back.repository', $repository);
    }

    /**
     * Unhooks a repository.
     *
     * @param string $repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unhook($repository)
    {
        $repository = $this->getRepository($repository, true);

        if (!$this->webhooks->remove(auth()->user(), $repository)) {
            $this->flash(
                'Failed to remove webhook, try relogging?',
                BaseController::FLASH_ERROR
            );
        } else {
            $this->flash(
                'Successfully removed webhook!',
                BaseController::FLASH_SUCCESS
            );
        }

        return redirect()->route('get::back.repository', $repository);
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
