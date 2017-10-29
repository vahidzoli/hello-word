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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Route::post('/api', 'LocationController@index');


Route::group(['prefix' => '/api'], function () {

    Route::get('/', 'ApiController@index');

    Route::get('/create' , 'HomeController@index');

    Route::post('/loc', 'ApiController@create');

    Route::get('/loc', 'ApiController@show_list');

    Route::get('/loc/{id}' , 'ApiController@show_one');

});