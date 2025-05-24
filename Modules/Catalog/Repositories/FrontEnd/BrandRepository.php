<?php

namespace Modules\Catalog\Repositories\FrontEnd;

use Modules\Catalog\Entities\Brand;
use Hash;
use DB;

class BrandRepository
{

    function __construct(Brand $brand)
    {
        $this->brand   = $brand;
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $brands = $this->brand->orderBy($order, $sort)->get();
        return $brands;
    }

    public function getPagination($order = 'id', $sort = 'desc')
    {
        $brands = $this->brand->orderBy($order, $sort)->paginate(5);
        return $brands;
    }


    public function getAllActive($order = 'id', $sort = 'desc')
    {
        $brands = $this->brand->active()->orderBy($order, $sort)->get();
        return $brands;
    }

    public function findBySlug($slug)
    {
        $brands = $this->brand->active()->where('slug->'.locale(), $slug)->first();
        return $brands;
    }

}
