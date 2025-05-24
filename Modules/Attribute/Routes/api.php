<?php

// ============================== attributes ========================
// attributes
Route::group(['prefix' => 'attributes', 'namespace' => 'Api'], function () {
    //global api
    Route::get("/", "AttributeController@index")->name("api.attributes")->middleware('cacheResponse');
    Route::get("get-by-id", "AttributeController@getById")->name("api.get_by_id")->middleware('cacheResponse');

});
