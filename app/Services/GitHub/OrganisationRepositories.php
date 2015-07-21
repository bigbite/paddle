<?php

namespace App\Services\GitHub;

use App\Models\User\User;
use App\Models\Repository\Repository;
use App\Models\Organisation\Organisation;
use Illuminate\Support\Collection;

class OrganisationRepositories extends Interactor
{
    /**
     * Lists a user's repositories.
     *
     * @param \App\Models\User\User                 $user
     * @param \App\Models\Organisation\Organisation $organisation
     * @param int                                   $page
     *
     * @return \Illuminate\Support\Collection|null
     */
    public function lists(User $user, Organisation $organisation, $page = 1)
    {
        $data = $this->get($user, 'orgs/'.$organisation->name.'/repos', [
            'per_page' => 100,
            'page' => $page,
            'type' => 'owner',
        ]);

        if ($data === null) {
            return;
        }

        $items = [];
        foreach ($data as $repository) {
            $items[] = [
                'id' => $repository['id'],
                'name' => $repository['name'],
            ];
        }

        return count($data) < 100 ? new Collection($items)
            : (new Collection($items))->merge($this->lists($user, $page + 1));
    }

    /**
     * Sync an organisation with new repositories.
     *
     * @param \App\Models\Organisation\Organisation $organisation
     * @param \Illuminate\Support\Collection        $repositories
     *
     * @return void
     */
    public function sync(Organisation $organisation, Collection $repositories)
    {
        $existing = $organisation->repositories;

        $repositories = $this->map($repositories);
        $repositories = $this->create($repositories, $existing);

        $organisation->repositories()->saveMany($repositories);
    }

    /**
     * Maps an array of string repositories to orginisation models.
     *
     * @param \Illuminate\Support\Collection $repositories
     *
     * @return \Illuminate\Support\Collection
     */
    protected function map(Collection $repositories)
    {
        return $repositories->map(function ($repository) {
            return new Repository($repository);
        });
    }

    /**
     * Creates inexistant repositories.
     *
     * @param \Illuminate\Support\Collection $all
     * @param \Illuminate\Support\Collection $existing
     *
     * @return \Illuminate\Support\Collection
     */
    protected function create(Collection $all, Collection $existing)
    {
        return $all->filter(function ($repository) use ($existing) {
            $existing = $existing->where('id', $repository->id)->first();

            if ($existing !== null) {
                $existing->update([
                    'name' => $repository->name,
                ]);

                return false;
            }

            return true;
        });
    }
}
