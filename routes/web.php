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

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::get('/', 'MainController@start');

Route::get('/add', 'MainController@add');

Route::get('/update/{id}', 'MainController@update');

Route::get('/auth', 'MainController@auth');

Route::get('/auth/{id}', 'MainController@auth');

/*Route::get('/post/{id}', function ($id) {
    return $id;
});*/
