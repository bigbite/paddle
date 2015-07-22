<?php

namespace App\Services\SVN;

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
     * Returns a command to be built.
     *
     * @return \App\Services\SVN\Command
     */
    public function build()
    {
        return new Command($this);
    }

    /**
     * The SVN binary path.
     *
     * @return string
     */
    protected function binary()
    {
        static $binary = null;

        if ($binary === null) {
            // Use ternary instead of default to avoid the exec
            // call if the env SVN_BINARY actually exists...

            $binary = env('SVN_BINARY') ?: `which svn`;
        }

        return $binary;
    }

    /**
     * Executes an SVN command.
     *
     * @param array|string $command
     * @param bool         $ssh
     *
     * @return [bool, string]
     */
    public function execute($command, $ssh = false)
    {
        if (is_array($command)) {
            $command = implode(' ', $command);
        }

        $output = '';

        with($process = new Process($this->binary().' '.$command))
            ->setWorkingDirectory($this->location)
            ->setTimeout(null)
            ->setIdleTimeout(null);

        if ($ssh) {
            $process->setEnv(['SVN_SSH' => env('SSH_KEY_PATH')]);
        }

        $process->run(function ($t, $buffer) use (&$output) {
                $output .= $buffer;
            });

        $response = [
            $successful = $process->isSuccessful(),
            $output,
        ];

        return $response;
    }
}
