<?php

namespace App\Console\Commands\Releases;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearOld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'releases:clear-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears out old releases.';

    /**
     * The deletable time.
     *
     * @var string
     */
    protected $deletable;

    /**
     * The base path for releases.
     *
     * @var string
     */
    protected $base;

    /**
     * The filesystem.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->deletable = time() - 60 * 60 * 1.5;
        $this->base = storage_path('releases');
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $directories = $this->files->directories($this->base);

        foreach ($directories as $directory) {
            $this->clearRepositories($directory);

            if ($this->isEmpty($directory)) {
                $this->files->deleteDirectory($directory);
            }
        }
    }

    /**
     * Clear out all the old repostories in a vendor folder.
     *
     * @param string $vendor
     *
     * @return void
     */
    protected function clearRepositories($vendor)
    {
        foreach ($this->files->directories($vendor) as $repository) {
            $this->clearRepository($repository);

            if (!$this->isEmpty($repository)) {
                continue;
            }

            $this->files->deleteDirectory($repository);
        }
    }

    /**
     * Clear out all the old releases in a repository.
     *
     * @param string $repository
     *
     * @return void
     */
    protected function clearRepository($repository)
    {
        foreach ($this->files->directories($repository) as $release)
        {
            if ($this->files->lastModified($release) > $this->deletable) {
                continue;
            }

            $this->files->deleteDirectory($release);
        }

        foreach ($this->files->files($repository) as $release)
        {
            if ($this->files->lastModified($release) > $this->deletable) {
                continue;
            }

            $this->files->delete($release);
        }
    }

    /**
     * Check if a directory is empty.
     *
     * @param string $directory
     *
     * @return bool
     */
    protected function isEmpty($directory)
    {
        return count($this->files->allFiles($directory)) === 0;
    }
}
