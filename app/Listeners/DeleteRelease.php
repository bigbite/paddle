<?php

namespace App\Listeners;

use App\Events\ReleaseWasUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteRelease implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ReleaseWasUploaded $event
     *
     * @return void
     */
    public function handle(ReleaseWasUploaded $event)
    {
        $this->remove($event->location);
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
