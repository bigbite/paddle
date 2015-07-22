<?php

namespace App\Listeners;

use Mail;
use App\Events\RepositoryWasReleased;
use App\Events\ReleaseWasDownloaded;
use App\Services\GitHub\Releases;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DownloadRelease implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The GitHub releases service.
     *
     * @var \App\Services\GitHub\Releases
     */
    protected $releases;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Releases $releases)
    {
        $this->releases = $releases;
    }

    /**
     * Handle the event.
     *
     * @param RepositoryWasReleased $event
     *
     * @return void
     */
    public function handle(RepositoryWasReleased $event)
    {
        if ($event->repository->processing !== $event->tag) {
            return;
        }

        if (!$event->repository->rigged) {
            $this->emailFailure(
                $event,
                $event->repository->getRouteKey().' doesn\'t have the SVN information, please set it up!'
            );

            return;
        }

        @mkdir($path = storage_path('releases/'.$event->repository->getRouteKey()), 0777, true);
        $path = tempnam($path, time());

        if (!$this->releases->pull($event->repository, $path, null, $event->tag)) {
            $this->release();

            $this->emailFailure(
                $event,
                $event->repository->getRouteKey().' failed to download for '.$event->tag.'!'.PHP_EOL.
                'Trying '.(3 - $this->attempts()).' more time(s)...'
            );

            return;
        }

        event(new ReleaseWasDownloaded($event->repository, $path, $event->tag));
    }

    /**
     * Notify of a failure via email.
     *
     * @param RepositoryWasReleased $event
     * @param string                $output
     *
     * @return void
     */
    protected function emailFailure(RepositoryWasReleased $event, $output)
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
}
