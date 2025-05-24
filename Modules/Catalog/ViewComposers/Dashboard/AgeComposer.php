<?php

namespace Modules\Catalog\ViewComposers\Dashboard;

use Modules\Catalog\Repositories\Dashboard\AgeRepository as AgeRepo;
use Illuminate\View\View;
use Cache;

class AgeComposer
{
    public $ages = [];

    public function __construct(AgeRepo $age)
    {
        $this->ages = $age->getAllActive();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('ages', $this->ages);
    }
}
