<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/pos', function (Request $request) {
    return $request->user();
});


/* Route::group(['prefix' => 'pos/areas'], function () {

    Route::get('countries', 'WebService\AreaController@countries')->name('pos.areas.countries.index');
    Route::get('cities/{id}', 'WebService\AreaController@cities')->name('pos.areas.cities.index');
    Route::get('states/{id}', 'WebService\AreaController@states')->name('pos.areas.cities.index');
}); */