<?php

namespace Modules\Catalog\ViewComposers\FrontEnd;

use Modules\Catalog\Repositories\FrontEnd\CategoryRepository as Category;
use Illuminate\View\View;
use Cache;

class CategoryComposer
{
    protected $headerCategories;

    public function __construct(Category $category)
    {
        $this->headerCategories = $category->getHeaderCategories();

        /* if (!in_array(request()->route()->getName(), ['frontend.categories.products'])) {
            $this->headerCategories = $category->getHeaderCategories();
        } */
    }

    public function compose(View $view)
    {
        $view->with([
            'headerCategories' => $this->headerCategories,
        ]);

        /* if (!in_array(request()->route()->getName(), ['frontend.categories.products'])) {
            $view->with([
                'headerCategories' => $this->headerCategories,
            ]);
        } */
    }
}
