<?php

/* Route::group(['prefix' => '/'], function () {
    Route::get('home', 'WebService\CatalogController@getHomeData')->name('api.home');
}); */

Route::group(['prefix' => 'catalog', 'namespace' => 'WebService'], function () {
    Route::get('categories', 'CatalogController@getCategories')->name('api.catalog.categories');
    Route::get('products/autocomplete', 'CatalogController@getAutoCompleteProducts')->name('api.catalog.get_autocomplete_products');
    Route::get('products', 'CatalogController@getProducts')->name('api.catalog.get_products');
    Route::get('ages', 'CatalogController@getAges')->name('api.catalog.get_ages');
    Route::get('product/{id}/details', 'CatalogController@getProductDetails')->name('api.catalog.get_product_details');
    Route::get('brands', 'CatalogController@getAllBrands')->name('api.catalog.brands');
});

Route::group(['namespace' => 'WebService\V2', 'prefix' => 'catalog/pos-service'], function () {

    Route::get('generate-sku', 'CatalogController@generateSku')->name('api.generate_sku');
    Route::get('all-categories', 'CatalogController@getAllCategories')->name('api.categories.list');
    Route::get('products', 'CatalogController@getProductsByCategory')->name('api.products_by_category');
    Route::get('product/{id}/details', 'CatalogController@getProductDetails');

    // Route::get('all-brands', 'BrandController@getAllBrands')->name('api.categories.brands');
});
