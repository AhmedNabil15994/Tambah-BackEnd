<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'brands'], function () {

  	Route::get('/' ,'Dashboard\BrandController@index')
  	->name('dashboard.brands.index')
    ->middleware(['permission:show_brands']);

  	Route::get('datatable'	,'Dashboard\BrandController@datatable')
  	->name('dashboard.brands.datatable')
  	->middleware(['permission:show_brands']);

  	Route::get('create'		,'Dashboard\BrandController@create')
  	->name('dashboard.brands.create')
    ->middleware(['permission:add_brands']);

  	Route::post('/'			,'Dashboard\BrandController@store')
  	->name('dashboard.brands.store')
    ->middleware(['permission:add_brands']);

  	Route::get('{id}/edit'	,'Dashboard\BrandController@edit')
  	->name('dashboard.brands.edit')
    ->middleware(['permission:edit_brands']);

  	Route::put('{id}'		,'Dashboard\BrandController@update')
  	->name('dashboard.brands.update')
    ->middleware(['permission:edit_brands']);

  	Route::delete('{id}'	,'Dashboard\BrandController@destroy')
  	->name('dashboard.brands.destroy')
    ->middleware(['permission:delete_brands']);

  	Route::get('deletes'	,'Dashboard\BrandController@deletes')
  	->name('dashboard.brands.deletes')
    ->middleware(['permission:delete_brands', 'permission:delete_all']);

  	Route::get('{id}','Dashboard\BrandController@show')
  	->name('dashboard.brands.show')
    ->middleware(['permission:show_brands']);

});
