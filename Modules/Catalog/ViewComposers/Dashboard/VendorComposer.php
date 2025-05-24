<?php

namespace Modules\Catalog\ViewComposers\Dashboard;

use Illuminate\View\View;
use Modules\Catalog\Repositories\Dashboard\VendorRepository as Vendor;

class VendorComposer
{
    public $activeVendors = [];

    public function __construct(Vendor $vendor)
    {
        $this->activeVendors = $vendor->getAllActive();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['activeVendors' => $this->activeVendors]);
    }
}
