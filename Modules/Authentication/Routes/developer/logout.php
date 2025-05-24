<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'logout','middleware' => 'auth:developer'], function () {

    // Logout
    Route::any('/', 'Developer\LoginController@logout')
    ->name('developer.logout');

});
