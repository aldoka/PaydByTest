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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

/** @var \Dingo\Api\Routing\Router $api */
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['middleware' => 'api.throttle'], function (\Dingo\Api\Routing\Router $api) {
    $api->get('podcasts/{status}', ['as' => 'podcasts.index', 'uses' => 'App\Http\Controllers\PodcastController@index'])
        ->where('status', '[A-Za-z]+');
    $api->get('podcasts/{id}', ['as' => 'podcasts.show', 'uses' => 'App\Http\Controllers\PodcastController@show'])
        ->where('id', '[0-9]+');
    $api->post('podcasts', ['as' => 'podcasts.store', 'uses' => 'App\Http\Controllers\PodcastController@store']);
    $api->put('podcasts/{id}', ['as' => 'podcasts.update', 'uses' => 'App\Http\Controllers\PodcastController@update']);
    $api->delete('podcasts/{id}', ['as' => 'podcasts.destroy', 'uses' => 'App\Http\Controllers\PodcastController@destroy']);
    $api->get('podcasts/approve/{id}', ['as' => 'podcasts.approve', 'uses' => 'App\Http\Controllers\PodcastController@approve']);
    $api->post('comments/{podcastId}', ['as' => 'comment.strore', 'uses' => 'App\Http\Controllers\CommentController@store']);
    $api->delete('comments/{id}', ['as' => 'comment.destroy', 'uses' => 'App\Http\Controllers\CommentController@destroy']);
});