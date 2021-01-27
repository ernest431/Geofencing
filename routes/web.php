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

Route::get('/', function () {
    return view('welcome');
});

// FireBase
Route::get('/firebase', 'FireBaseController@create');

// Pages
Route::get('/home', 'PagesController@dashboard');
// Route::get('/maps', 'PagesController@maps');

// Pasien
Route::get('/pasien', 'PasienController@index');
Route::get('/pasien/create', 'PasienController@create');
Route::post('/pasien', 'PasienController@store');
Route::get('/pasien/{id}/edit', 'PasienController@edit');

Route::get('/getdata', 'PasienController@getData');
Route::get('/maps', 'PasienController@location');
Route::get('/lokasi', 'PasienController@getLocation');
Route::get('/testnotif', 'PasienController@testNotif');

// Test
Route::get('/test', 'PasienController@showData');

Route::get('/firebase/test', function(){
    return view('Message.index');
});


