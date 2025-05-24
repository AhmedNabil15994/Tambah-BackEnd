<?php

Route::group(['prefix' => 'cart'], function () {

    Route::get('/', 'WebService\CartController@index')->name('api.cart.index');
    Route::post('add-or-update', 'WebService\CartController@createOrUpdate')->name('api.cart.add');
    Route::post('remove/{id}', 'WebService\CartController@remove')->name('api.cart.remove');
    Route::post('remove-condition/{name}', 'WebService\CartController@removeCondition')->name('api.cart.remove_condition');
    Route::post('add-company-delivery-fees-condition', 'WebService\CartController@addCompanyDeliveryFeesCondition')->name('api.cart.add_company_delivery_fees_condition');
    Route::post('clear', 'WebService\CartController@clear')->name('api.cart.clear');

    Route::group(['prefix' => 'gift'], function () {
        Route::post('/add-gift/{id}', 'WebService\CartController@addGiftToCart')->name('api.cart.add_gift');
        Route::post('/remove-cart-gift/{id}', 'WebService\CartController@removeCartGift')->name('api.cart.remove_cart_gift');
    });

    Route::group(['prefix' => 'card'], function () {
        Route::post('/add-cart-card/{id}', 'WebService\CartController@addOrUpdateCartCard')->name('api.shopping-cart.add_card');
        Route::post('/remove-cart-card/{id}', 'WebService\CartController@removeCartCard')->name('api.shopping-cart.remove_cart_card');
    });

    Route::group(['prefix' => 'addons'], function () {
        Route::post('/add-cart-addons/{id}', 'WebService\CartController@addOrUpdateCartAddons')->name('api.shopping-cart.add_addons');
        Route::post('/remove-cart-addons/{id}', 'WebService\CartController@removeCartAddons')->name('api.shopping-cart.remove_cart_addons');
    });

    Route::group(['prefix' => 'pos-service', 'namespace' => 'WebService\V2'], function () {

        Route::get('/', 'CartController@index')->name('api.cart.index');
        Route::post('add-or-update', 'CartController@createOrUpdate')->name('api.cart.add');
        Route::post('remove/{id}', 'CartController@remove')->name('api.cart.remove');
        Route::post(
            'remove-condition/{name}',
            'CartController@removeCondition'
        )->name('api.cart.remove_condition');
        Route::post(
            'add-company-delivery-fees-condition',
            'CartController@addCompanyDeliveryFeesCondition'
        )->name('api.cart.add_company_delivery_fees_condition');
        Route::post('clear', 'CartController@clear')->name('api.cart.clear');
        Route::get('/handle-draft', 'CartController@handleDraft')->name('api.cart.handleDraft');
        Route::post('/repalce-cart', 'CartController@handleCartReplace')->name('api.cart.repaceCart');
        Route::post("/update-item-price", 'CartController@updatePriceItem');
        Route::post('add-or-update-sku', 'CartController@createOrUpdateFromSku')->name('api.cart.add.sku');
    });


    Route::group(['prefix' => 'v2'], function () {
        Route::group(['prefix' => 'gift'], function () {
            Route::post('/wrapping', 'WebService\WrappingCartController@wrappingCartProducts')->name('api.cart.gifts.v2.add');
            Route::post('/remove-cart-gift/{id}', 'WebService\WrappingCartController@removeCartGift')->name('api.cart.gifts.v2.remove');
        });

        Route::group(['prefix' => 'card'], function () {
            Route::post('/add-cart-card', 'WebService\WrappingCartController@addOrUpdateCartCard')->name('api.cart.cards.add');
            Route::post('/remove-cart-card', 'WebService\WrappingCartController@removeCartCard')->name('api.cart.cards.remove');
        });

        Route::group(['prefix' => 'addons'], function () {
            Route::post('/add-cart-addons', 'WebService\WrappingCartController@addOrUpdateCartAddons')->name('api.cart.addons.add');
            Route::post('/remove-cart-addons/{id}', 'WebService\WrappingCartController@removeCartAddons')->name('api.cart.addons.remove');
        });
    });
});
