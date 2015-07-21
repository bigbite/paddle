<?php

namespace App\Services\GitHub;

use App\Models\User\User;
use App\Models\Repository\Repository;

class Webhooks extends Interactor
{
    /**
     * Applys a webhook to a repository.
     *
     * @param \App\Models\User\User             $user
     * @param \App\Models\Repository\Repository $repository
     *
     * @return bool
     */
    public function apply(User $user, Repository $repository)
    {
        $data = $this->post($user, '/repos/'.$repository->getRouteKey().'/hooks', [
            'name' => 'web',
            'config' => [
                'url' => route('post::webhook'),
                'content_type' => 'json',
                'secret' => env('WEBHOOK_SECRET'),
            ],
            'events' => ['pull'],
            'active' => true,
        ]);

        if ($data === null) {
            return false;
        }

        $repository->update([
            'hook_id' => $data['id'],
        ]);

        return true;
    }

    /**
     * Removes a webhook from a repository.
     *
     *@param \App\Models\User\User $user
     * @param \App\Models\Repository\Repository $repository
     *
     * @return bool
     */
    public function remove(User $user, Repository $repository)
    {
        $this->delete($user, '/repos/'.$repository->getRouteKey().'/hooks/'.$repository->hook_id);

        $repository->update([
            'hook_id' => null,
        ]);

        return true;
    }
}
