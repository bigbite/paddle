<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Models\Repository\Repository;

class ReleaseWasExtracted extends Event
{
    use SerializesModels;

    /**
     * The repository.
     *
     * @var \App\Models\Repository\Repository
     */
    public $repository;

    /**
     * The location.
     *
     * @var string
     */
    public $location;

    /**
     * The tag.
     *
     * @var string
     */
    public $tag;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Repository\Repository $repository
     * @param string                                 $location
     * @param string                                 $tag
     *
     * @return void
     */
    public function __construct(Repository $repository, $location, $tag)
    {
        $this->repository = $repository;
        $this->location = $location;
        $this->tag = $tag;
    }
}
