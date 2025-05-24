<?php

namespace Modules\Catalog\Traits;

use Modules\Catalog\Entities\Category;

trait CatalogTrait
{
    public function getAllSubCategoryIds($categoryId)
    {
        $cat = Category::active()->find($categoryId);
        $allCats = [];
        if (!is_null($cat))
            $allCats = $cat->getAllRecursiveChildren()->pluck('id')->toArray();
        return $allCats;
    }
}
