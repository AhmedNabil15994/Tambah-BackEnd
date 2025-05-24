<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'slider'], function () {

  	Route::get('/' ,'Dashboard\SliderController@index')
  	->name('dashboard.slider.index')
    ->middleware(['permission:show_slider']);

    Route::get('datatable'	,'Dashboard\SliderController@datatable')
    ->name('dashboard.slider.datatable')
    ->middleware(['permission:show_slider']);

  	Route::get('create'		,'Dashboard\SliderController@create')
  	->name('dashboard.slider.create')
    ->middleware(['permission:add_slider']);

  	Route::post('/'			,'Dashboard\SliderController@store')
  	->name('dashboard.slider.store')
    ->middleware(['permission:add_slider']);

  	Route::get('{id}/edit'	,'Dashboard\SliderController@edit')
  	->name('dashboard.slider.edit')
    ->middleware(['permission:edit_slider']);

  	Route::put('{id}'		,'Dashboard\SliderController@update')
  	->name('dashboard.slider.update')
    ->middleware(['permission:edit_slider']);

  	Route::delete('{id}'	,'Dashboard\SliderController@destroy')
  	->name('dashboard.slider.destroy')
    ->middleware(['permission:delete_slider']);

  	Route::get('deletes'	,'Dashboard\SliderController@deletes')
  	->name('dashboard.slider.deletes')
    ->middleware(['permission:delete_slider', 'permission:delete_all']);

  	Route::get('{id}','Dashboard\SliderController@show')
  	->name('dashboard.slider.show')
    ->middleware(['permission:show_slider']);

});

Route::group(['prefix' => 'banner'], function () {

  	Route::get('/' ,'Dashboard\BannerController@index')
  	->name('dashboard.banner.index')
    ->middleware(['permission:show_banner']);

    Route::get('datatable'	,'Dashboard\BannerController@datatable')
    ->name('dashboard.banner.datatable')
    ->middleware(['permission:show_banner']);

  	Route::get('create'		,'Dashboard\BannerController@create')
  	->name('dashboard.banner.create')
    ->middleware(['permission:add_banner']);

  	Route::post('/'			,'Dashboard\BannerController@store')
  	->name('dashboard.banner.store')
    ->middleware(['permission:add_banner']);

  	Route::get('{id}/edit'	,'Dashboard\BannerController@edit')
  	->name('dashboard.banner.edit')
    ->middleware(['permission:edit_banner']);

  	Route::put('{id}'		,'Dashboard\BannerController@update')
  	->name('dashboard.banner.update')
    ->middleware(['permission:edit_banner']);

  	Route::delete('{id}'	,'Dashboard\BannerController@destroy')
  	->name('dashboard.banner.destroy')
    ->middleware(['permission:delete_banner']);

  	Route::get('deletes'	,'Dashboard\BannerController@deletes')
  	->name('dashboard.banner.deletes')
    ->middleware(['permission:delete_banner', 'permission:delete_all']);

  	Route::get('{id}','Dashboard\BannerController@show')
  	->name('dashboard.banner.show')
    ->middleware(['permission:show_banner']);

});

Route::group(['prefix' => 'instagrams'], function () {

  	Route::get('/' ,'Dashboard\InstagramController@index')
  	->name('dashboard.instagrams.index')
    ->middleware(['permission:show_instagrams']);

    Route::get('datatable'	,'Dashboard\InstagramController@datatable')
    ->name('dashboard.instagrams.datatable')
    ->middleware(['permission:show_instagrams']);

  	Route::get('create'		,'Dashboard\InstagramController@create')
  	->name('dashboard.instagrams.create')
    ->middleware(['permission:add_instagrams']);

  	Route::post('/'			,'Dashboard\InstagramController@store')
  	->name('dashboard.instagrams.store')
    ->middleware(['permission:add_instagrams']);

  	Route::get('{id}/edit'	,'Dashboard\InstagramController@edit')
  	->name('dashboard.instagrams.edit')
    ->middleware(['permission:edit_instagrams']);

  	Route::put('{id}'		,'Dashboard\InstagramController@update')
  	->name('dashboard.instagrams.update')
    ->middleware(['permission:edit_instagrams']);

  	Route::delete('{id}'	,'Dashboard\InstagramController@destroy')
  	->name('dashboard.instagrams.destroy')
    ->middleware(['permission:delete_instagrams']);

  	Route::get('deletes'	,'Dashboard\InstagramController@deletes')
  	->name('dashboard.instagrams.deletes')
    ->middleware(['permission:delete_instagrams']);

  	Route::get('{id}','Dashboard\InstagramController@show')
  	->name('dashboard.instagrams.show')
    ->middleware(['permission:show_instagrams']);

});
