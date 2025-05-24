<?php

Route::group(['prefix' => 'coupons'], function () {

  	Route::post('/check_coupon' ,'WebService\CouponController@checkCoupon')
  	->name('api.check_coupon');


});
