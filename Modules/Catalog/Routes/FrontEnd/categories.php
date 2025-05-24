<?php

Route::get('categories', 'FrontEnd\CategoryController@index')
    ->name('frontend.categories.index');


Route::get('category/ajax/{slug?}', 'FrontEnd\CategoryController@productsCategory')
    ->name('frontend.categories.products.ajax');

Route::get('category/children/{slug}', 'FrontEnd\CategoryController@categoryChildren')
    ->name('frontend.categories.children');

Route::get('category/{slug?}', 'FrontEnd\CategoryController@productsCategory')
    ->name('frontend.categories.products');
