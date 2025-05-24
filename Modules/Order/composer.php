<?php

view()->composer(
    [
        'apps::dashboard.index',
        'order::dashboard.shared._filter',
        'order::dashboard.shared._bulk_order_actions',
        'pos::dashboard.pos_orders.index',
    ],
    \Modules\Order\ViewComposers\Dashboard\OrderStatusComposer::class
);

view()->composer(
    [
        'setting::dashboard.index',
        'apps::dashboard.layouts._aside',
    ],
    \Modules\Order\ViewComposers\Dashboard\OrderStatusSettingComposer::class
);

view()->composer(
    [
        'order::dashboard.orders.show',
        'order::dashboard.shared._filter',
    ],
    \Modules\Order\ViewComposers\Dashboard\PaymentComposer::class
);
