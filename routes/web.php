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

Route::get('/', 'ViewController\home@home');

Route::get('/login', 'ViewController\login@needLogin');

Route::prefix('admin')->group(function () {
    Route::get('/', 'ViewController\Admin\home@home');

    Route::prefix('/user')->group(function () {
        Route::get('/', 'ViewController\Admin\home@user');

        Route::get('/list', 'ViewController\Admin\user@userList');
    });
});