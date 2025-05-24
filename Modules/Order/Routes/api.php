<?php

Route::post('webhooks', 'WebService\OrderController@webhooks')->name('frontend.orders.webhooks');

Route::group(['prefix' => 'orders'], function () {

    Route::post('create', 'WebService\OrderController@createOrder')->name('api.orders.create');
    Route::post('{id}/cancel', 'WebService\OrderController@cancelOrderPayment')->name('api.orders.cancel');
    Route::get('success-tap', 'WebService\OrderController@successTap')->name('api.orders.success.tap');
    Route::get('success', 'WebService\OrderController@success')->name('api.orders.success');
    Route::get('failed', 'WebService\OrderController@failed')->name('api.orders.failed');

    Route::get('success-myFatoorah', 'WebService\OrderController@callbackMyFatoorah')->name('api.orders.myfatoorah.callback');

    // Route::get('html-order/{id}/show', 'WebService\OrderController@displayHtmlOrder')->name('api.orders.html_order');

    Route::post('{id}/charge/{chargeId}', 'WebService\OrderController@getPaymentChargeData')->name('api.orders.get_payment_charge_data');
    Route::post('{id}/create-charge/{tapToken}', 'WebService\OrderController@createPaymentChargeData')->name('api.orders.create_payment_charge_data');

    Route::group(['prefix' => '/', 'middleware' => 'auth:api'], function () {

        Route::get('list', 'WebService\OrderController@userOrdersList')->name('api.orders.index');
        Route::get('{id}/details', 'WebService\OrderController@getOrderDetails')->name('api.orders.details');
        Route::post('{id}/rate', 'WebService\OrderController@orderRate')->name('api.orders.rate');

    });
});
