<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'login'], function () {

        // Show Login Form
        Route::get('/', 'Developer\LoginController@showLogin')
        ->name('developer.login.view')
        ->middleware('guest');

        // Submit Login
        Route::post('/', 'Developer\LoginController@postLogin')
        ->name('developer.login');
});
