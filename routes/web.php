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

/*Route::post('/forms', 'FormsController@store');
Route::get('/forms', 'FormsController@index');
Route::get('/forms/{forms}/{data}', 'FormsController@render')->where(['forms', '[0-9]+']);*/

Route::post('/forms/get', 'FormsController@render');