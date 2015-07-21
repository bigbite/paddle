<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use App\Models\Repository\Repository;

class ReleaseCommandsWereExecuted extends Event
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
     * The output.
     *
     * @var string
     */
    public $output;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Repository\Repository $repository
     * @param string                            $location
     * @param string                            $tag
     * @param string                            $output
     *
     * @return void
     */
    public function __construct(Repository $repository, $location, $tag, $output)
    {
        $this->repository = $repository;
        $this->location = $location;
        $this->tag = $tag;
        $this->output = $output;
    }
}
