<?php

namespace App\Listeners;

use App\Events\ReleaseWasExtracted;
use App\Events\ReleaseCommandsWereExecuted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Command\Interactor as Command;

class ExecuteReleaseCommands implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The Command interactor.
     *
     * @var \App\Services\Command\Interactor
     */
    protected $command;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * Handle the event.
     *
     * @param ReleaseWasExtracted $event
     *
     * @return void
     */
    public function handle(ReleaseWasExtracted $event)
    {
        if ($event->repository->processing !== $event->tag) {
            return;
        }

        list($success, $output) = $this->command
            ->at($event->location.'/trunk')
            ->execute();

        if (!$success) {
            $this->release();
            $this->emailFailure($event, $output);
            $this->remove($event->location);

            return;
        }

        event(new ReleaseCommandsWereExecuted($event->repository, $event->location, $event->tag, $output));
    }

    /**
     * Notify of a failure via email.
     *
     * @param ReleaseWasExtracted $event
     * @param string                $output
     *
     * @return void
     */
    protected function emailFailure(ReleaseWasExtracted $event, $output)
    {
        $repository = $event->repository;

        Mail::send([
            'emails.notice.failure.html',
            'emails.notice.failure.text'
        ], [
            'repository' => $repository,
            'tag' => $event->tag,
            'output' => $output,
        ], function ($message) use ($repository) {
            $message->to($repository->email, $repository->getRouteKey())
                ->subject('Paddle Repository Notice');
        });
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
