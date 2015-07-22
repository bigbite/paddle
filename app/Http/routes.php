<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$router->pattern('organisation', '[^\\/]+');
$router->pattern('repository', '[^\\/]+\\/[^\\/]+');

$router->get('/', ['as' => 'get::front.home', 'uses' => 'FrontController@home']);

$router->get('/sign-in', ['as' => 'get::oauth', 'uses' => 'OAuthController@get']);
$router->get('/sign-in/redirect', ['as' => 'get::oauth.redirect', 'uses' => 'OAuthController@redirect']);
$router->get('/sign-in/callback', ['as' => 'get::oauth.callback', 'uses' => 'OAuthController@callback']);
$router->get('/sign-out', ['as' => 'get::oauth.out', 'uses' => 'OAuthController@out']);

$router->post('/09151301-c742-4fa9-bcf6-3bd601d9f40e', ['as' => 'post::webhook', 'uses' => 'WebhookController@handle']);

$router->group(['middleware' => 'auth', 'namespace' => 'Back'], function ($router) {

    $router->get('/home', ['as' => 'get::back', 'uses' => 'HomeController@get']);
    $router->get('/sync', ['as' => 'get::back.sync', 'uses' => 'SyncController@get']);

    $router->group(['namespace' => 'Organisations'], function ($router) {

        $router->get('/organisation/{organisation}', ['as' => 'get::back.organisation', 'uses' => 'IndexController@get']);
        $router->get('/organisation/{organisation}/sync', ['as' => 'get::back.organisation.sync', 'uses' => 'SyncController@get']);

    });

    $router->group(['namespace' => 'Repositories'], function ($router) {

        $router->get('/repository/{repository}', ['as' => 'get::back.repository', 'uses' => 'RepositoryController@show']);
        $router->post('/repository/{repository}', ['as' => 'post::back.repository', 'uses' => 'RepositoryController@update']);

        $router->get('/repository/{repository}/hook', ['as' => 'get::back.repository.hook', 'uses' => 'HookController@hook']);
        $router->get('/repository/{repository}/unhook', ['as' => 'get::back.repository.unhook', 'uses' => 'HookController@unhook']);

        $router->get('/repository/{repository}/release{version?}', ['as' => 'get::back.repository.release', 'uses' => 'ReleaseController@release'])
            ->where('version', '/[^/]+');

    });

});
