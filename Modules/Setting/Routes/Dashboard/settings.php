<?php

Route::group(['prefix' => 'setting','middleware' => ['tocaan.user']], function () {

    // Show Settings Form
    Route::get('/', 'Dashboard\SettingController@index')
    ->name('dashboard.setting.index');

    // Update Settings
    Route::post('/', 'Dashboard\SettingController@update')
    ->name('dashboard.setting.update');

});

Route::group(['prefix' => 'marketing/social'], function () {

    Route::get('/', 'Dashboard\SocialController@index')
    ->name('dashboard.social.index');

    Route::post('/', 'Dashboard\SocialController@update')
    ->name('dashboard.social.update');

});
