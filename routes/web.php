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

Route::get('/','uploadFile@index');

Route::get('openType','uploadFile@openType');

Route::post('postFile','uploadFile@upload')->name('postFile');

Route::post('saveData','uploadFile@saveData')->name('saveData');
