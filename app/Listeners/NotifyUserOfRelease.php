<?php

namespace App\Listeners;

use Mail;
use App\Events\ReleaseWasUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserOfRelease implements ShouldQueue
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
        $repository = $event->repository;
        $data = $event->data;

        $template = 'emails.notice.'.(array_get($data, 'success') ? 'success' : 'failure');
        Mail::send([
            $template.'.html',
            $template.'.text',
        ], [
            'repository' => $repository,
            'tag' => $event->tag,
            'output' => array_get($data, 'output'),
        ], function ($message) use ($repository, $data) {
            $message->to($repository->email, $repository->getRouteKey())
                ->subject('Paddle Repository Notice - "'.array_get($data, 'message').'"');
        });
    }
}
