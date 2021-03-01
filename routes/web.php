<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('register', 'AuthController@register');
        $router->post('login',    'AuthController@login');
    });

    $router->group(['middleware' => 'auth'], function ($router) {
        $router->post('deposit',           'UserController@deposit');
        $router->get('{currency}/balance', 'UserController@balance');
        $router->get('statement',          'UserController@getStatement');

        $router->group(['prefix' => 'crypto/{currency}'], function ($router) {
            $router->get('price',         'CryptoController@getPrice');
            $router->post('{action}',     'CryptoController@transact');
            $router->get('position',      'CryptoController@getPosition');
            $router->get('volume',        'CryptoController@getTransactedVolume');
            $router->get('price-history', 'CryptoController@getPriceHistory');
        });
    });
});
