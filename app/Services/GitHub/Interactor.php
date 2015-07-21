<?php

namespace App\Services\GitHub;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use App\Models\User\User;

class Interactor
{
    /**
     * Sends a get request to GitHub.
     *
     * @param string                $method
     * @param \App\Models\User\User $user
     * @param string                $route
     * @param array                 $query
     * @param array                 $headers
     *
     * @return mixed|null
     */
    protected function json($method, User $user, $route, $query = [], $headers = [])
    {
        $payload = $method === 'get' ? 'query' : 'json';

        try {
            $response = app(GuzzleClient::class)->{$method}('https://api.github.com/'.ltrim($route, '/'), [
                'headers' => [
                    'Accept' => 'application/vnd.github.moondragon+json',
                    'Authorization' => 'token '.$user->token,
                ] + $headers,
                $payload => $query,
            ]);
        } catch (Exception $e) {
            return;
        }

        return $response->json();
    }

    /**
     * Download a file and store it locally.
     *
     * @param string $token
     * @param string $link
     * @param string $destination
     *
     * @return bool
     */
    protected function download($token, $link, $destination)
    {
        if (strpos($link, 'http') !== 0) {
            $link = 'https://api.github.com/'.ltrim($link, '/');
        }

        try {
            $response = app(GuzzleClient::class)->get($link, [
                'save_to' => $destination,
                'headers' => [
                    'Authorization' => 'token '.$token,
                ],
            ]);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Allows for magic :method methods for Http verbs.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (!in_array($method, ['get', 'post', 'put', 'patch', 'delete', 'options'])) {
            throw new Exception("Method {$method} doesn't exist!");
        }

        array_unshift($args, $method);

        return call_user_func_array([$this, 'json'], $args);
    }
}
