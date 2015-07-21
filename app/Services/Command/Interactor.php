<?php

namespace App\Services\Command;

use Symfony\Component\Process\Process;

class Interactor
{
    /**
     * The location of the repo.
     *
     * @var string
     */
    protected $location;

    /**
     * Constructs the Interactor.
     *
     * @param string $location
     */
    public function __construct($location = '')
    {
        $this->location = $location;
    }

    /**
     * Changes the working directory.
     *
     * @param string $location
     *
     * @return $this
     */
    public function at($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Executes an SVN command.
     *
     * @param array|string $command
     *
     * @return [bool, string]
     */
    public function execute()
    {
        $script = rtrim($this->location, '/').'/.paddle.sh';

        if (!file_exists($script)) {
            return [true, 'No .paddle.sh to run!'];
        }

        $output = '';

        with($process = new Process('sh '.$script))
            ->setWorkingDirectory($this->location)
            ->setTimeout(null)
            ->setIdleTimeout(null)
            ->run(function ($t, $buffer) use (&$output) {
                $output .= $buffer;
            });

        $response = [
            $successful = $process->isSuccessful(),
            $output,
        ];

        return $response;
    }
}
