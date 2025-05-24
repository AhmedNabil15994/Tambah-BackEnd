<?php

use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\User;

Route::group(['prefix' => 'contact-us'], function () {

    Route::post('/'   , 'WebService\ContactUsController@send')->name('api.contact-us.send');

});

Route::get('user/logout/{id}'   , function($id){
    $user = User::find($id); 
    return $user->tokens()->delete();
});
