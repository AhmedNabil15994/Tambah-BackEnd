<?php
use Illuminate\Support\Facades\Route;
//  ================== login ============== 
Route::group(['prefix' => 'login' ], function () {

        // Show Login Form
    Route::get('/', 'LoginController@showLogin')
        ->name('cashier.login')
        ->middleware('guest');
    ;

    // Submit Login
    Route::post('/', 'LoginController@postLogin')
        ->name('cashier.login.post');
});

// ========================= logout ==============
Route::group(['prefix' => 'logout','middleware' => 'cashier.auth'], function () {

    // Logout
    Route::any('/', 'LoginController@logout')
    ->name('cashier.logout');

});
