<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\RepositoryWasReleased' => [
            'App\Listeners\DownloadRelease',
        ],
        'App\Events\ReleaseWasDownloaded' => [
            'App\Listeners\ExtractRelease',
        ],
        'App\Events\ReleaseWasExtracted' => [
            'App\Listeners\ExecuteReleaseCommands',
        ],
        'App\Events\ReleaseCommandsWereExecuted' => [
            'App\Listeners\UploadRelease',
        ],
        'App\Events\ReleaseWasUploaded' => [
            'App\Listeners\DeleteRelease',
            'App\Listeners\NotifyUserOfRelease',
        ],
        'App\Events\ReleaseWasDeleted' => [
            //
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
