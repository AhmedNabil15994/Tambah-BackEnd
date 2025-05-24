<?php

Route::group(['prefix' => 'companies'], function () {
    /*Route::get('brands', 'WebService\CatalogController@brands');*/
    Route::get('default-company', 'WebService\CompanyController@getDefaultCompany');
});
