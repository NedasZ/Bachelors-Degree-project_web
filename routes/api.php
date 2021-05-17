<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('login', 'App\Http\Controllers\ApiController@loginUser');
Route::post('register', 'App\Http\Controllers\ApiController@registerUser');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'App\Http\Controllers\ApiController@details');
    
    Route::get('event/join/{eventName}', 'App\Http\Controllers\ApiController@joinEvent');
    Route::get('event/{eventId}','App\Http\Controllers\ApiController@getEventData');
    Route::put('event/{eventId}/users','App\Http\Controllers\ApiController@editEventUsers');
    Route::get('userEvents/{userId}','App\Http\Controllers\ApiController@getUserEvents');
    Route::get('userEvents/{userId}/{eventId}','App\Http\Controllers\ApiController@getUserEventData');
    Route::post('event/saveResults', 'App\Http\Controllers\ApiController@SaveResults');
    Route::post('event/saveLocation', 'App\Http\Controllers\ApiController@SaveUserLocation');
    Route::get('event/{eventId}/end','App\Http\Controllers\ApiController@endEvent');
    });













