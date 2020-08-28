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

Route::get('/','uploadFile@index')->name('/');

Route::post('postFile','uploadFile@upload')->name('postFile');

Route::post('saveData','uploadFile@saveData')->name('saveData');

Route::get('showData','uploadFile@showData')->name('showData');

Route::post('editName','uploadFile@updateName')->name('editName');

Route::get('deleteFont/{id}','uploadFile@deleteFont')->name('deleteFont');

Route::get('testRoute',function (){ return 'TestRoute';});

Route::redirect('/dia-chi-cu', '/dia-chi-moi', $statusCode = 301);

Route::view('/welcome', 'welcome', $datas = ['name' => 'ABC']);

Route::prefix('admin')->group(function () {
    Route::get('users', function () {
        // admin/users
    });
    Route::get('settings', function () {
        // admin/settings
    });
});
