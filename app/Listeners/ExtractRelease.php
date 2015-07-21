<?php

namespace App\Listeners;

use ZipArchive;
use App\Events\ReleaseWasDownloaded;
use App\Events\ReleaseWasExtracted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\SVN\Interactor as SVN;

class ExtractRelease implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The SVN interactor.
     *
     * @var \App\Services\SVN\Interactor
     */
    protected $svn;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SVN $svn)
    {
        $this->svn = $svn;
    }

    /**
     * Handle the event.
     *
     * @param ReleaseWasDownloaded $event
     *
     * @return void
     */
    public function handle(ReleaseWasDownloaded $event)
    {
        $path = storage_path('releases/'.$event->repository->getRouteKey());
        $location = time().'-'.str_random(16);
        if (!mkdir($path.'/'.$location, 0777, true)) {
            $this->release();

            return;
        }

        $archive = new ZipArchive();

        if (!$archive->open($event->location)) {
            $this->remove($path.'/'.$location);
            $this->release();

            return;
        }

        list($success, $output) = $this->svn->at($path.'/'.$location)->build()
            ->checkout()
            ->unsafe(rtrim($event->repository->svn, '/'))
            ->safe('./')
            ->username($event->repository->username)
            ->password($event->repository->password)
            ->execute();

        if (!$success) {
            $this->release();

            return;
        }

        $dir = rtrim(array_get($archive->statIndex(0), 'name'), '/');

        $archive->extractTo($path.'/'.$location);
        $archive->close();

        $this->remove($path.'/'.$location.'/trunk');

        if (!rename($path.'/'.$location.'/'.$dir, $path.'/'.$location.'/trunk')) {
            $this->remove($path.'/'.$location);
            $this->release();

            return;
        }

        unlink($event->location);

        event(new ReleaseWasExtracted($event->repository, $path.'/'.$location, $event->tag));
    }

    /**
     * Removes a directory and it's contents.
     *
     * @param string $directory
     *
     * @return void
     */
    protected function remove($directory)
    {
        foreach (glob("{$directory}/*") as $file) {
            if (is_dir($file)) {
                $this->remove($file);
            } else {
                @unlink($file);
            }
        }

        foreach (glob("{$directory}/.*") as $file) {
            if ($file == "{$directory}/." || $file == "{$directory}/..") {
                continue;
            }

            if (is_dir($file)) {
                $this->remove($file);
            } else {
                @unlink($file);
            }
        }

        @rmdir($directory);
    }
}
