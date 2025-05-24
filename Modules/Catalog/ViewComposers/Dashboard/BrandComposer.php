<?php

namespace Modules\Catalog\ViewComposers\Dashboard;

use Modules\Catalog\Repositories\Dashboard\BrandRepository as BrandRepo;
use Illuminate\View\View;

class BrandComposer
{
    public $brands = [];

    public function __construct(BrandRepo $brand)
    {
        $this->brands = $brand->getAllActive();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('brands', $this->brands);
    }
}
