<?php

namespace Modules\Area\ViewComposers\Dashboard;

use Modules\Area\Repositories\Dashboard\CountryRepository as Country;
use Illuminate\View\View;
use Modules\Area\Entities\CurrencyCode;
use Setting;

class CurrencySettingComposer
{
    public $currency;

    public function __construct(CurrencyCode $currency)
    {
        $this->currency = $currency->get(['id','name']);
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('currencies' , $this->currency);
        $view->with('default_currency' , Setting::get('default_currency'));
        $view->with('supported_currencies' , Setting::get('supported_currencies'));
    }

}
