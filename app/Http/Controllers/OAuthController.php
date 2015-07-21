<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User\User;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Illuminate\Auth\Guard;

class OAuthController extends Controller
{
    /**
     * The GitHub provider instance.
     *
     * @var \Laravel\Socialite\Two\GithubProvider
     */
    protected $socialite;

    /**
     * Constructs the OAuthController.
     *
     * @param \Laravel\Socialite\Contracts\Factory
     */
    public function __construct(Socialite $socialite)
    {
        $this->socialite = $socialite->driver('github')
            ->scopes([
                'user:email',
                'public_repo',
                'repo',
                'read:repo_hook',
                'write:repo_hook',
                'admin:repo_hook',
                'admin:org_hook',
            ]);

        $this->middleware('guest', [
            'except' => 'out',
        ]);
    }

    /**
     * Shows the sign in form.
     *
     * @return \Illuminate\View\View
     */
    public function get()
    {
        return $this->socialite->redirect();
    }

    /**
     * Redirects to the provider.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        return $this->socialite->redirect();
    }

    /**
     * Attempts to authenticate the user.
     *
     * @param \App\Models\User\User  $user
     * @param \Illuminate\Auth\Guard $auth
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(User $user, Guard $auth)
    {
        try {
            $payload = $this->socialite->user();
        } catch (Exception $e) {
            $this->flash('Failed to sign in via GitHub, did you decline?', Controller::FLASH_ERROR);

            return redirect()->route('get::front.home');
        }

        try {
            $response = app('GuzzleHttp\\Client')->get('https://api.github.com/user/emails?access_token='.$payload->token, [
                'headers' => [
                    'Accept' => 'application/vnd.github.v3+json',
                ],
            ]);

            $emails = $response->json();
        } catch (Exception $e) {
            $this->flash('Failed to sign in via GitHub, is your GitHub account verified?', Controller::FLASH_ERROR);

            return redirect()->route('get::front.home');
        }

        $email = array_filter($emails, function ($email) {
            return $email['primary'];
        });

        if (count($email) === 0)
        {
            $this->flash('Failed to sign in via GitHub, is your GitHub account verified?', Controller::FLASH_ERROR);

            return redirect()->route('get::front.home');
        }

        $email = array_get(array_values($email)[0], 'email');

        $user = $user->firstOrCreate([
            'uid' => $payload->id,
        ]);

        $user->update([
            'token' => $payload->token,
            'email' => $email,
            'nickname' => $payload->nickname,
            'name' => $payload->nickname,
        ]);

        $auth->login($user, true);

        return redirect()->intended('/home');
    }

    /**
     * Signs the user out.
     *
     * @param \Illuminate\Auth\Guard $auth
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function out(Guard $auth)
    {
        $auth->logout();

        return redirect('/');
    }
}
