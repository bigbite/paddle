<?php

namespace App\Services\GitHub;

use App\Models\User\User;
use App\Models\Organisation\Organisation;
use Illuminate\Support\Collection;

class Organisations extends Interactor
{
    /**
     * Lists a user's organisations.
     *
     * @param \App\Models\User\User $user
     * @param int                   $page
     *
     * @return \Illuminate\Support\Collection|null
     */
    public function lists(User $user, $page = 1)
    {
        $data = $this->get($user, 'user/orgs', [
            'per_page' => 100,
            'page' => $page,
        ]);

        if ($data === null) {
            return;
        }

        $items = [];
        foreach ($data as $organisation) {
            $items[] = [
                'id' => $organisation['id'],
                'name' => $organisation['login'],
            ];
        }

        return count($data) < 100 ? new Collection($items)
            : (new Collection($items))->merge($this->lists($user, $page + 1));
    }

    /**
     * Sync a user with new organisations.
     *
     * @param \App\Models\User\User          $user
     * @param \Illuminate\Support\Collection $organisations
     *
     * @return void
     */
    public function sync(User $user, Collection $organisations)
    {
        $existing = Organisation::query()
            ->whereIn('id', $organisations->lists('id'))
            ->get();

        $user->organisations()->detach();

        $organisations = $this->map($organisations);
        $organisations = $this->create($organisations, $existing);

        $user->organisations()->attach($organisations->lists('id')->toArray());
    }

    /**
     * Maps an array of string organisations to orginisation models.
     *
     * @param \Illuminate\Support\Collection $organisations
     *
     * @return \Illuminate\Support\Collection
     */
    protected function map(Collection $organisations)
    {
        return $organisations->map(function ($organisation) {
            return new Organisation($organisation);
        });
    }

    /**
     * Creates inexistant organisations.
     *
     * @param \Illuminate\Support\Collection $all
     * @param \Illuminate\Support\Collection $existing
     *
     * @return \Illuminate\Support\Collection
     */
    protected function create(Collection $all, Collection $existing)
    {
        return $all->map(function ($organisation) use ($existing) {
            $existing = $existing->where('id', $organisation->id)->first();

            if ($existing !== null) {
                $organisation = $existing->fill([
                    'name' => $organisation->name,
                ]);
            }

            $organisation->save();

            return $organisation;
        });
    }
}
