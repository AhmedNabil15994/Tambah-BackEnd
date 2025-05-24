<?php

namespace Modules\Catalog\ViewComposers\Dashboard;

use Modules\Catalog\Repositories\Dashboard\ProductRepository as Product;
use Illuminate\View\View;
use Cache;

class ProductComposer
{
    public $products;
    public $sharedActiveProducts;

    public function __construct(Product $product)
    {
        $exceptedIds = [];
        $this->products = $product->getAll();
        if (request()->route()->getName() == 'dashboard.products.edit' && !is_null(request()->route('id'))) {
            $exceptedIds[] = request()->route('id');
        }
        $this->sharedActiveProducts = $product->getAllActive('id', 'desc', $exceptedIds);
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['products' => $this->products, 'sharedActiveProducts' => $this->sharedActiveProducts]);
    }
}
