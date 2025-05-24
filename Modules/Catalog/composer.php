<?php

// Dashboard ViewComposr
view()->composer([
    'catalog::dashboard.categories.*',
    'catalog::dashboard.products.*',
    'advertising::dashboard.advertising.*',
    'notification::dashboard.notifications.*',
    'slider::dashboard.sliders.*',
    'slider::dashboard.banner.*',
    'coupon::dashboard.*',
], \Modules\Catalog\ViewComposers\Dashboard\CategoryComposer::class);

// Dashboard ViewComposr
view()->composer([
    'advertising::dashboard.advertising.*',
    'notification::dashboard.notifications.*',
    'slider::dashboard.sliders.*',
    'slider::dashboard.banner.*',
    'catalog::dashboard.products.create',
    'catalog::dashboard.products.edit',
    'catalog::dashboard.products.clone',
], \Modules\Catalog\ViewComposers\Dashboard\ProductComposer::class);

view()->composer([
    'coupon::dashboard.*',
], \Modules\Catalog\ViewComposers\Dashboard\ProductComposer::class);

// FrontEnd ViewComposer
view()->composer([
    //'apps::frontend.layouts.header-section',
    //'apps::frontend.layouts.footer-section',
    'apps::frontend.layouts.master',
], \Modules\Catalog\ViewComposers\FrontEnd\CategoryComposer::class);

// Dashboard View Composer
view()->composer([
    'catalog::dashboard.products.*',
], \Modules\Catalog\ViewComposers\Dashboard\SearchKeywordComposer::class);

view()->composer([
    'catalog::dashboard.products.create',
    'catalog::dashboard.products.edit',
    'catalog::dashboard.products.clone',
], \Modules\Catalog\ViewComposers\Dashboard\AgeComposer::class);

view()->composer([
    'catalog::dashboard.products.create',
    'catalog::dashboard.products.edit',
    'catalog::dashboard.products.clone',
], \Modules\Catalog\ViewComposers\Dashboard\BrandComposer::class);

view()->composer([
    'catalog::dashboard.products.create',
    'catalog::dashboard.products.edit',
    'catalog::dashboard.products.clone',
    'catalog::dashboard.products.index',
    'report::dashboard.reports.product-sales',
], \Modules\Catalog\ViewComposers\Dashboard\VendorComposer::class);
