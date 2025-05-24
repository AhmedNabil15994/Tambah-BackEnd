<?php

namespace Modules\Catalog\Repositories\WebService;

use Modules\Catalog\Entities\Brand;

class BrandRepository
{
    protected $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    public function getAllBrands($request)
    {
        $query = $this->brand->active();
        $query = $query->whereHas('products', function ($query) {
            $query->active();
        });
        if ($request->show_in_home == 'yes') {
            $query = $query->where('show_in_home', 1);
        }
        return $query->latest()->get();
    }
}
