<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'FrontEnd\HomeController@index')->name('frontend.home');
//    ->middleware('cacheResponse');
Route::get('/filter', 'FrontEnd\HomeController@filter')->name('frontend.home.filter');
//    ->middleware('cacheResponse');
