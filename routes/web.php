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


Route::get('/', 'MainController@start')->middleware('googleauth');


Route::post('/add', 'AddController@main')->middleware('verifyadd');
Route::post('/delete', 'DeleteController@main')->middleware('verifydelete');
Route::post('/update', 'UpdateController@main')->middleware('verifyupdate');

Route::get('/asdf', 'MainController@asdf');

//Route::post('/update', 'EventController@update')->middleware('googleauth')->middleware('verifyupdate');
//Route::post('/delete', 'EventController@delete')->middleware('googleauth')->middleware('verifydelete');
//Route::post('/add', 'EventController@add')->middleware('googleauth')->middleware('verifyadd');

Route::get('/auth', 'MainController@auth');
Route::post('/auth', 'MainController@auth');
