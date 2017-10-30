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

//Route::get('/home', 'HomeController@index')->name('home');


Route::group(['prefix' => '/api'], function () {

    //Menu
    Route::get('/', 'ApiController@index');

    //show form
    Route::get('/create' , 'HomeController@index');

    //create new location
    Route::post('/loc', 'ApiController@create');

    //location list
    Route::get('/loc', 'ApiController@show_list');

    //show one location
    Route::get('/loc/{id}' , 'ApiController@show_one');

    //update one location
    Route::PUT('/loc/update/{id}' , 'ApiController@update');

    //delete one direction
    Route::DELETE('/loc/delete/{id}' , 'ApiController@destroy');

    //show near car on map
    Route::get('/near' , 'ApiController@nearby');

});
