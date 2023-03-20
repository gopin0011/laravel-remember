<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
Route::group(['namespace' => 'App\Http\Controllers'], function()
{ 
    Route::get('/', function () {
        return view('welcome');
    });

    Route::group(['middleware' => 'web'], function () {
        Route::get('/login', 'AuthController@show')->name('login.show');
        Route::post('/login', 'AuthController@login')->name('auth.login');
        Route::get('/logout', 'AuthController@logout')->name('auth.logout');

        // convert encrypted users table
        Route::get('/convert', 'MyController@convert');
    });

});
Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('APIkey');
    