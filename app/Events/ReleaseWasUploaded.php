<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Models\Repository\Repository;

class ReleaseWasUploaded extends Event
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
     * The data.
     *
     * @var array
     */
    public $data;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Repository\Repository $repository
     * @param string                                 $location
     * @param string                                 $tag
     * @param bool                                   $data
     *
     * @return void
     */
    public function __construct(Repository $repository, $location, $tag, $data)
    {
        $this->repository = $repository;
        $this->location = $location;
        $this->tag = $tag;
        $this->data = $data;
    }
}
