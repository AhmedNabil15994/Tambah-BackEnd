<?php

view()->composer([
    'area::dashboard.cities.*',
    'area::dashboard.states.*',
    'catalog::dashboard.products.edit',
    'catalog::dashboard.products.create',
    'company::dashboard.companies.create',
    'company::dashboard.companies.edit',
    'order::dashboard.shared._filter',
], \Modules\Area\ViewComposers\Dashboard\CountryComposer::class);

view()->composer([
    'setting::dashboard.index',
], \Modules\Area\ViewComposers\Dashboard\CountrySettingComposer::class);

view()->composer([
    'setting::dashboard.index',
], \Modules\Area\ViewComposers\Dashboard\CurrencySettingComposer::class);

view()->composer(
    [

        'order::dashboard.shared._filter',
    ],
    \Modules\Area\ViewComposers\Dashboard\CityComposer::class
);


view()->composer(
    [
        //    'apps::frontend.index',
        //    'vendor::frontend.vendors.*',
        //    'user::frontend.profile.*',
    ],
    \Modules\Area\ViewComposers\FrontEnd\StateComposer::class
);


view()->composer(
    [
        //    'catalog::frontend.address.*',
        //    'catalog::frontend.address.index',
        //    'user::frontend.profile.addresses.address',
        //    'user::frontend.profile.addresses.create',
    ],
    \Modules\Area\ViewComposers\FrontEnd\CityComposer::class
);

view()->composer(
    [
        //    'catalog::frontend.address.*',
        //    'catalog::frontend.address.index',
        //    'user::frontend.profile.addresses.address',
        //    'user::frontend.profile.addresses.create',
        //    'catalog::frontend.checkout.*',
    ],
    \Modules\Area\ViewComposers\FrontEnd\StateComposer::class
);

view()->composer(
    [
        //    'catalog::frontend.address.*',
        //    'catalog::frontend.address.index',
        'catalog::frontend.checkout.*',
        'user::frontend.profile.addresses.components.country-selector.selector',
        //    'user::frontend.profile.addresses.create',
    ],
    \Modules\Area\ViewComposers\FrontEnd\CountryComposer::class
);

view()->composer(
    [
        'user::frontend.profile.index',
    ],
    \Modules\Area\ViewComposers\FrontEnd\currencyComposer::class
);
