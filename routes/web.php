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

Route::get('/', function () {
    return view('welcome');
});

Route::post('home/leave','TasksController@leave');
Route::get('home/search','TasksController@search');
Route::get('home/search_ajax','TasksController@search_ajax');

Auth::routes();




Route::resource('home', 'TasksController');
