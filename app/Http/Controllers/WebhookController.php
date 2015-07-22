<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Repository\Repository;
use App\Events\RepositoryWasReleased;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WebhookController extends Controller
{
    /**
     * The event handlers.
     *
     * @var array
     */
    protected static $handlers = [
        'ping' => 'handlePing',
        'push' => 'handlePush',
    ];

    /**
     * Handles a webhook.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function handle(Request $request)
    {
        $signature = $request->header('X-Hub-Signature');

        if ($signature !== ($realSignature = 'sha1='.hash_hmac('sha1', $request->getContent(), env('WEBHOOK_SECRET')))) {
            throw new NotFoundHttpException();
        }

        $handler = array_get(
            static::$handlers,
            $request->header('X-Github-Event')
        );

        if (!$handler) {
            throw new NotFoundHttpException();
        }

        return app()->call([$this, $handler]);
    }

    /**
     * Handle a ping event.
     *
     * @return void
     */
    public function handlePing()
    {
        //
    }

    /**
     * Handle a push event.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function handlePush(Request $request)
    {
        $repositories = $this->getRepositories(
            $request->input('repository.full_name'),
            true
        );

        $ref = $request->input('ref');

        if ($ref !== 'refs/heads/'.env('GIT_BRANCH', 'master')) {
            return;
        }

        $repository = $repositories->first();
        $pushedAt = $request->input('repository.pushed_at');

        if (!is_int($pushedAt)) {
            $pushedAt = Carbon::parse($pushedAt)->timestamp;
        }

        if ($pushedAt <= $repository->pushed_at) {
            return;
        }

        $release = $request->input('after');

        $repository->update([
            'processing' => $release,
            'pushed_at' => $pushedAt,
        ]);

        event(new RepositoryWasReleased($repository, $release));
    }

    /**
     * Get all repositories of a specific name.
     *
     * @param string $repository
     * @param bool   $hooked
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getRepositories($repository, $hooked = false)
    {
        list($vendor, $package) = explode('/', $repository, 2);

        $repositories = Repository::query()
            ->where('vendor', $vendor)
            ->where('package', $package)
            ->{'where'.($hooked ? 'NotNull' : 'Null')}('hook_id')
            ->get();

        if (!$repositories || $repositories->count() === 0) {
            throw new NotFoundHttpException();
        }

        return $repositories;
    }
}
