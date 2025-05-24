<?php
Route::group(['prefix' => 'labels', "namespace"=> "Dashboard"], function () {

  	Route::get('/' ,'ProductLabelController@index')
  	->name('dashboard.labels.index')
    ->middleware(['permission:show_labels']);

    Route::get('/search' ,'ProductLabelController@search')
     ->name('dashboard.labels.search')
     ->middleware(['permission:show_labels']);

     Route::any('/render-label' ,'ProductLabelController@renderLabel')
     ->name('dashboard.labels.renderLabel')
     ->middleware(['permission:show_labels']);

  

});