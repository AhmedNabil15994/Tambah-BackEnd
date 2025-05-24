<?php

namespace Modules\Area\ViewComposers\Dashboard;

use Modules\Area\Repositories\Dashboard\CountryRepository as Country;
use Illuminate\View\View;
use Cache;

class CountryComposer
{
    public $countries, $query, $supportedCountries, $activeCountries;

    public function __construct(Country $country)
    {
        $this->query = $country->getQuery();
        $this->supportedCountries =  $this->query;
        $this->countries =  $this->query;
        $this->activeCountries =  $this->query;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('countries' , $this->countries->orderBy('id','desc')->get());
        $view->with('activeCountries' , $this->countries->active()->orderBy('id','desc')->get());
        $view->with('supported_countries' , $this->supportedCountries->whereIn('id', config('setting.supported_countries') ?? [])->orderBy('id','desc')->get());
    }

}
