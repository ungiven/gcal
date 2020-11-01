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


Route::get('/', 'MainController@index')->middleware('googleauth');


Route::post('/add', 'AddController@main')->middleware('googleauth')->middleware('verifyadd');
Route::post('/delete', 'DeleteController@main')->middleware('googleauth')->middleware('verifydelete');
Route::post('/update', 'UpdateController@main')->middleware('googleauth')->middleware('verifyupdate');

Route::get('/auth', 'AuthController@index');
Route::post('/auth', 'AuthController@auth');
