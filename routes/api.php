<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => ['api'],
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function (\Illuminate\Routing\Router $router) {
    $router->post('articles/uploadImage', 'ArticlesController@uploadImage');
    $router->delete('articles/deleteImage', 'ArticlesController@deleteImage');
    $router->get('articles/count', 'ArticlesController@count');
    $router->apiResource('articles', 'ArticlesController');
    $router->apiResource('categories', 'CategoriesController');
    $router->apiResource('topics', 'TopicsController');
    $router->apiResource('tags', 'TagsController');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::apiResource('articles', ArticlesController::class)->only(['index', 'show']);
Route::namespace('Api')->group(function (\Illuminate\Routing\Router $router) {
    // 在 「App\Http\Controllers\Api」 命名空间下的控制器
    $router->get('article-ids', 'ArticlesController@ids');
    $router->apiResource('articles', 'ArticlesController')->only(['index', 'show']);
    $router->apiResource('categories', 'CategoriesController')->only(['index', 'show']);
    $router->apiResource('pages', 'PagesController')->only(['show']);
    $router->apiResource('links', 'LinksController')->only(['index']);
});
