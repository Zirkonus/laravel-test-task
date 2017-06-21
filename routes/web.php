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
Route::get('/', function(){
	return view('welcome');
});
    Route::get('/{app}', 'ContactController@showContacts')->name('show_users');
    Route::get('/{app}/add', 'ContactController@addUser');
    Route::post('/{app}/add', 'ContactController@addUserTo');
    Route::get('/{app}/add/{id}', 'ContactController@addUserById');
