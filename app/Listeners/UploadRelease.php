<?php

namespace App\Listeners;

use App\Events\ReleaseCommandsWereExecuted;
use App\Events\ReleaseWasUploaded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\SVN\Interactor as SVN;

class UploadRelease implements ShouldQueue
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
     * @param ReleaseCommandsWereExecuted $event
     *
     * @return void
     */
    public function handle(ReleaseCommandsWereExecuted $event)
    {
        $this->svn->at($event->location);

        $message = $event->tag.' - Paddle';

        $output = '>>> Executing Commands'.PHP_EOL.$this->format($event->output, $event);

        $output .= '>>> Adding'.PHP_EOL;
        list(, $_output) = $this->svn->build()
            ->add()
            ->safe('trunk/*')
            ->execute(true);
        $output .= $this->format($_output, $event);

        $this->svn->build()
            ->rm()
            ->safe('tags/'.basename($event->tag))
            ->m(true, $message)
            ->force()
            ->execute(true);

        $this->remove($event->location.'/tags/'.basename($event->tag));

        $output .= '>>> Resolving'.PHP_EOL;
        list(, $_output) = $this->svn->build()
            ->resolve()
            ->accept('working')
            ->safe('trunk')
            ->execute();
        $output .= $this->format($_output, $event);

        $output .= '>>> Copying'.PHP_EOL;
        $this->svn->build()
            ->cp()
            ->safe('trunk')
            ->unsafe('tags/'.basename($event->tag))
            ->execute(true);
        $output .= $this->format($_output, $event);

        $output .= '>>> Committing'.PHP_EOL;
        list($success, $_output) = $this->svn->build()
            ->commit()
            ->m(true, $message)
            ->username($event->repository->username)
            ->password($event->repository->password)
            ->execute();
        $output .= $this->format($_output, $event);

        $output .= '>>> Committed "' . $message . '"';

        event(new ReleaseWasUploaded(
            $event->repository,
            $event->location,
            $event->tag,
            compact('success', 'output', 'message')
        ));
    }

    /**
     * Formats the output.
     *
     * @param string              $output
     * @param ReleaseCommandsWereExecuted $event
     *
     * @return string
     */
    protected function format($output, ReleaseCommandsWereExecuted $event)
    {
        $output = trim($output);
        $output = str_replace(realpath($event->location), '', $output);
        $output = explode(PHP_EOL, $output);
        $output = array_map(function ($line) {
            return '    '.$line;
        }, $output);
        $output = implode(PHP_EOL, $output);

        return $output . PHP_EOL;
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
