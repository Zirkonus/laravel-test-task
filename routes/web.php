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
    Route::get('/', 'ContactController@showContacts')->name('show_users');
    Route::get('/add', 'ContactController@addUser');
    Route::post('/add', 'ContactController@addUserTo');
    Route::get('/add/{id}', 'ContactController@addUserById');
