<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Routing\Router;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin', function () {
    return view('backend');
});

Route::get('/ie-not-support', function () {
    return view('ie-not-support');
});

Route::group([
    'middleware' => ['web', 'api'],
    'prefix' => 'api/admin',
    'namespace' => 'Admin\Auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
    Route::put('changeUserInfo', 'AuthController@changeUserInfo');
    Route::post('resetPassword', 'AuthController@resetPassword');
});

Route::group([
    'prefix'     => '',
    'middleware' => ['web'],
    'namespace'  => 'Api\Auth',
], function (Router $router) {
    $router->get('oauth/login/{type}', 'OAuthController@login');
    $router->get('oauth/callback/{type}', 'OAuthController@callback');
});

Route::group([
    'prefix'     => 'api',
    'middleware' => ['oauth'],
    'namespace'  => 'Api\Auth',
], function (Router $router) {
    $router->get('oauth/logout', 'OAuthController@logout');
    $router->get('oauth/me', 'OAuthController@me');
});
