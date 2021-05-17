<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/event_list', [App\Http\Controllers\EventController::class, 'list_all'])->name('event_list');
Route::get('/event_list/event/{event_id}', [App\Http\Controllers\EventController::class, 'event_info']);

Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::get('upgrade', function () {return view('pages.upgrade');})->name('upgrade'); 
	 Route::get('map', function () {return view('pages.maps');})->name('map');
	 Route::get('icons', function () {return view('pages.icons');})->name('icons'); 
	 Route::get('table-list', function () {return view('pages.tables');})->name('table');
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	// Mano Routes
	Route::get('event', ['as' => 'event.setup', 'uses' => 'App\Http\Controllers\EventController@setup']);
	Route::put('event', ['as' => 'event.publish', 'uses' => 'App\Http\Controllers\EventController@publish']);
	Route::get('event_list/user', [App\Http\Controllers\EventController::class, 'list_user'])->name('user_event_list');
	Route::get('event_list/event/{event_id}/edit', [App\Http\Controllers\EventController::class, 'event_edit']);
	Route::get('event_list/event/{event_id}/getResults', [App\Http\Controllers\EventController::class, 'event_get_results']);
	Route::get('event_list/event/{event_id}/end', [App\Http\Controllers\EventController::class, 'end_event']);
	Route::put('event_list/event/{event_id}/edit', [App\Http\Controllers\EventController::class, 'event_update'])->name('event_update');
	Route::post('map_upload', [App\Http\Controllers\EventController::class, 'map_upload'])->name('upload_map');
	Route::post('map_update', [App\Http\Controllers\EventController::class, 'map_update'])->name('map_update');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
