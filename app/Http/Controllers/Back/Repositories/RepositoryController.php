<?php

namespace App\Http\Controllers\Back\Repositories;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RepositoryController extends BaseController
{
    /**
     * Shows the home view.
     *
     * @param string $repository
     *
     * @return \Illuminate\View\View
     */
    public function show($repository)
    {
        return view('back.repository.show', [
            'repository' => $this->getRepository($repository),
        ]);
    }

    /**
     * Attempts to update the repository.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $repository)
    {
        $repository = $this->getRepository($repository);

        $this->validate($request, [
            'svn' => [
                'required',
                'url',
            ],
            'username' => [
                'required',
                'max:30',
            ],
            'password' => [
                'max:1024',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);

        $repository->update($request->only('svn', 'username', 'password', 'email'));

        $this->flash('Repository successfully updated!', BaseController::FLASH_SUCCESS);

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
