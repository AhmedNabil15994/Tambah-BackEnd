<?php

view()->composer([
    'order::dashboard.orders.show',
    'order::vendor.orders.show',
    'order::dashboard.shared._filter',
    'order::vendor.shared._filter',

], \Modules\User\ViewComposers\Dashboard\DriverComposer::class);

//view()->composer(['vendor::dashboard.vendors.*'], \Modules\User\ViewComposers\Dashboard\SellerComposer::class);

view()->composer([
    'coupon::dashboard.*',
    'order::dashboard.*',
    'pos::cashier.index',
    'setting::dashboard.tabs.other',
], \Modules\User\ViewComposers\Dashboard\UserComposer::class);

view()->composer(
    [
        'catalog::frontend.address.*',
        'catalog::frontend.address.index',
    ],
    \Modules\User\ViewComposers\FrontEnd\UserAddressesComposer::class
);

view()->composer(
    [
        "pos::dashboard.reports.order-sales",
        "pos::dashboard.reports.order-refund",
        "pos::dashboard.reports.cashier-order",
    ],
    \Modules\User\ViewComposers\Dashboard\CashierComposer::class
);
