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

//Route::get('/', function () {
//    return view('index');
//});
//
//
//Route::get('/welcome', function () {
//    return view('welcome');
//});

Route::get('/ie-not-support', function () {
    return view('ie-not-support');
});

Route::get('/ssr', function () {
//    dd(ssr('assets/js/app-server.js')->render());
    return view('ssr');

});


Route::resource('/articles', 'ArticlesController')->only(['index', 'show']);

Route::get('/:category_name', function (\Illuminate\Routing\Router $router) {
    return view('pages/articles');
});

Route::get('/topic/:topic_name', function (\Illuminate\Routing\Router $router) {
    return view('pages/articles');
});

Route::get('/tag/:tag_name', function (\Illuminate\Routing\Router $router) {
    return view('pages/articles');
});

//Route::group([
//    'prefix'        => 'api',
//    'middleware' => ['web'],
//    'namespace'  => 'App\Http\Controllers',
//], function (\Illuminate\Routing\Router $router) {
//    $router->any('oauth/login/{type}','OAuthController@login');
//    $router->any('oauth/callback/{type}', 'OAuthController@callback');
////    $router->any('api/{action}',function($action){
////        $ctrl = \App::make("\\App\\Http\\Controllers\\ApiController");
////        return \App::call([$ctrl, $action]);
////    });
//    $router->apiResource('articles', 'ArticlesController')->only(['index', 'show']);
//});
