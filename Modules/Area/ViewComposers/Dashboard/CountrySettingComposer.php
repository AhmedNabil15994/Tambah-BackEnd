<?php

namespace Modules\Area\ViewComposers\Dashboard;

use Modules\Area\Repositories\Dashboard\CountryRepository as Country;
use Illuminate\View\View;
use Setting;

class CountrySettingComposer
{
    public $query;

    public function __construct(Country $country)
    {
        $this->query = $country->getQuery();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $countries = $this->query;
        $view->with('countries' , $countries->pluck('title','id')->toArray());
        $view->with('default_country' , Setting::get('default_country'));
    }

}
