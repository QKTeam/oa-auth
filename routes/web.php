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

Route::get('/', 'ViewController\home@home')->name('home');

Route::get('/login', 'ViewController\login@needLogin');

Route::get('/reset', 'ViewController\login@resetPassword');

Route::get('/update', 'ViewController\login@updateProfile')->middleware('check');

Route::prefix('admin')->group(function () {
    Route::get('/', 'ViewController\Admin\home@home');

    Route::prefix('/user')->group(function () {
        Route::get('/', 'ViewController\Admin\home@user');

        Route::get('/list', 'ViewController\Admin\user@userList');

        Route::get('/create', 'ViewController\Admin\user@createUser');

        Route::get('/update/{user_id}', 'ViewController\Admin\user@update');
    });

    Route::prefix('/role')->group(function () {
        Route::get('/', 'ViewController\Admin\home@role');

        Route::get('/list', 'ViewController\Admin\role@roleList');

        Route::get('/create', 'ViewController\Admin\role@createRole');

        Route::get('/update/{role_id}', 'ViewController\Admin\role@update');
    });

    Route::prefix('/link')->group(function () {
        Route::get('/', 'ViewController\Admin\home@link');

        Route::get('/list', 'ViewController\Admin\link@linkList');

        Route::get('/create', 'ViewController\Admin\link@createLink');

        Route::get('/update/{link_id}', 'ViewController\Admin\link@update');
    });
});