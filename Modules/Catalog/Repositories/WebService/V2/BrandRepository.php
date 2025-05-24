<?php

namespace Modules\Catalog\Repositories\WebService\V2;

use Illuminate\Support\Facades\DB;
use Modules\Catalog\Entities\Brand;

class BrandRepository
{
    protected $brand;

    function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    public function getAllBrands($request)
    {
        return $this->brand->active()->latest()->get();
    }
}
