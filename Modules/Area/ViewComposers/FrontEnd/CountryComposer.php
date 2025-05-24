<?php

namespace Modules\Area\ViewComposers\FrontEnd;

use Modules\Area\Repositories\FrontEnd\CountryRepository as Country;
use Illuminate\View\View;

class CountryComposer
{
    public $countriesTree;
    public $countries;

    public function __construct(Country $country)
    {
        $this->countries = $country->getAllNameAndId();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['countries' => $this->countries]);
    }
}
