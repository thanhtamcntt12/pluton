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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'beacon'], function () {
	Route::get('list', ['uses'=> 'BeaconController@index']);
	Route::post('add', ['uses'=>'BeaconController@add']);
	Route::post('edit', ['uses'=>'BeaconController@edit']);
	Route::post('rev', ['uses'=>'BeaconController@changeRev']);
	Route::post('delete', ['uses'=>'BeaconController@delete']);
	Route::get('detail', ['uses'=>'BeaconController@detail']);
	Route::get('list_add_guide', ['uses'=> 'BeaconController@list_add_guide']);
	Route::get('list_edit_guide', ['uses'=> 'BeaconController@list_edit_guide']);
});
Route::group(['prefix' => 'guide'], function () {
	Route::get('list',['uses'=>'GuideController@index']);
	Route::post('add', ['uses'=>'GuideController@add']);
	Route::post('edit', ['uses'=>'GuideController@edit']);
	Route::get('detail', ['uses'=>'GuideController@detail']);
	Route::post('delete', ['uses'=>'GuideController@delete']);
});

Route::group(['prefix' => 'app'], function () {
	Route::post('sync',['uses'=>'BeaconController@sync']);
});