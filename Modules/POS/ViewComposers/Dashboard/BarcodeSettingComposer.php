<?php

namespace Modules\POS\ViewComposers\Dashboard;

use Modules\POS\Repositories\Dashboard\BarcodeRepository as Barcode;
use Illuminate\View\View;
use Cache;

class BarcodeSettingComposer
{
    public $barcodes = [];

    public function __construct(Barcode $barcode)
    {
        $this->barcodes =  $barcode->getAllActive();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('barcodes' , $this->barcodes);
    }
}
