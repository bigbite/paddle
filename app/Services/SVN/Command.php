<?php

namespace App\Services\SVN;

class Command
{
    /**
     * The SVN interactor.
     *
     * @var \App\Services\SVN\Interactor
     */
    protected $svn;

    /**
     * The command.
     *
     * @var array
     */
    protected $command = [];

    /**
     * Constructs the Command.
     *
     * @param \App\Services\SVN\Interactor $svn
     */
    public function __construct(Interactor $svn)
    {
        $this->svn = $svn;
    }

    /**
     * Passes an unsafe argument.
     *
     * @param string $arg
     *
     * @return $this
     */
    public function unsafe($arg)
    {
        return $this->safe(escapeshellarg($arg));
    }

    /**
     * Passes a safe argument.
     *
     * @param string $arg
     *
     * @return $this
     */
    public function safe($arg)
    {
        $this->command[] = $arg;

        return $this;
    }

    /**
     * Executes the command.
     *
     * @param bool $ssh
     *
     * @return [bool, string]
     */
    public function execute($ssh = false)
    {
        return $this->svn->execute($this->command, $failable);
    }

    /**
     * Allows for magic :method methods for command args.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (count($args) === 0) {
            return $this->safe($method);
        }

        $prefix = '--';
        if (count($args) === 2) {
            $prefix = array_shift($args) ? '-' : '--';
        }

        return $this->safe($prefix.$method)->unsafe(array_shift($args));
    }
}
