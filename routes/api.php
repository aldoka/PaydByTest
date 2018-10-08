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

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['middleware' => 'api.throttle'], function ($api) {
    $api->get('podcasts/{status}', 'App\Http\Controllers\PodcastController@index')->where('status', '[A-Za-z]+');
    $api->get('podcasts/{id}', 'App\Http\Controllers\PodcastController@show')->where('id', '[0-9]+');
});
