<?php

namespace Modules\POS\Http\Controllers\Cashier;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use Modules\Catalog\Transformers\WebService\BrandResource;


use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Catalog\Repositories\WebService\BrandRepository as Brand;

class BrandController extends WebServiceController
{
    protected $brand;
   

    function __construct(Brand $brand)
    {
        $this->brand = $brand;
       
    }

   
    public function getAllBrands(Request $request): JsonResponse
    {
        $brands = $this->brand->getAllBrands($request);
        return $this->response(BrandResource::collection($brands));
    }

     

}
