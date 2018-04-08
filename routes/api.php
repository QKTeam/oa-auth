<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'AuthController@login');

Route::put('reset', 'UserController@reset');

Route::get('logStatus', 'AuthController@checkStatus')->middleware('check');

Route::get('isAdmin', 'AuthController@isAdmin')->middleware('check');

Route::get('getAuth', 'AuthController@getAuth')->middleware('check');

//Route::get('mail/send', 'MailController@send');

Route::prefix('/user')->group(function () {
    Route::post('/create', 'AuthController@register');

    Route::delete('/delete', 'UserController@delete');

    Route::put('/update/{user_id}', 'UserController@change');
}); // ->middleware('isAdmin');

Route::prefix('/link')->group(function () {
    Route::post('/create', 'LinkController@createLink');

    Route::delete('/delete', 'LinkController@delete');

    Route::put('/update', 'LinkController@change');
}); // ->middleware('isAdmin');

Route::prefix('/role')->group(function () {
    Route::post('/create', 'RoleController@createRole');

    Route::delete('/delete', 'RoleController@delete');

    Route::put('/update', 'RoleController@change');
}); // ->middleware('isAdmin');