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
Route::get('near','ApiController@nearby');

//show form
//Route::get('add' , 'ApiController@add');


Route::group(['prefix' => '/api'], function () {

    //Menu
    Route::get('/', 'ApiController@index');

    //location list
    Route::get('loc', 'ApiController@show_list');

    //show one location
    Route::get('loc/{id}' , 'ApiController@show_one');

    //result without db used
    Route::get('ajax' , 'ApiController@ajax');

    //result when retrieve data from database
    Route::get('base' , 'ApiController@base');

//    Route::get('draw' , 'ApiController@draw');
//    Route::get('set' , 'ApiController@set_coordinate');

//    //edit form
//    Route::get('edit/{id}' , 'ApiController@edit');
//
//    //create new location
//    Route::post('loc', 'ApiController@create');
//
//    //update one location
//    Route::PUT('edit/{id}/update' , 'ApiController@update');
//
//    //delete one direction
//    Route::DELETE('loc/{id}/delete' , 'ApiController@destroy');
});
