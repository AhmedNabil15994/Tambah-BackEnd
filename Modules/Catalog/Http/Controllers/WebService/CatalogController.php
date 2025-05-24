<?php

namespace Modules\Catalog\Http\Controllers\WebService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Catalog\Http\Requests\WebService\ProductRequest;
use Modules\Catalog\Repositories\WebService\CatalogRepository as Catalog;
use Modules\Catalog\Repositories\WebService\BrandRepository as Brand;
use Modules\Catalog\Transformers\WebService\BrandResource;
use Modules\Catalog\Transformers\WebService\AgeResource;
use Modules\Catalog\Transformers\WebService\AutoCompleteProductResource;
use Modules\Catalog\Transformers\WebService\CategoryResource;
use Modules\Catalog\Transformers\WebService\ProductResource;

class CatalogController extends WebServiceController
{
    protected $catalog;
    protected $brand;

    public function __construct(Catalog $catalog, Brand $brand)
    {
        $this->catalog = $catalog;
        $this->brand = $brand;
    }

    public function getCategories(Request $request)
    {
        $items = $this->catalog->getCategories($request);

        if ($request->response_type == 'paginated') {
            return $this->responsePagination(CategoryResource::collection($items));
        } else {
            return $this->response(CategoryResource::collection($items));
        }

    }

    public function getAutoCompleteProducts(Request $request)
    {
        $items = $this->catalog->getAutoCompleteProducts($request);
        $result = AutoCompleteProductResource::collection($items);
        return $this->response($result);
    }

    public function getAges(Request $request)
    {
        $items = $this->catalog->getAges($request);
        return $this->response(AgeResource::collection($items));
    }

    public function getProducts(ProductRequest $request)
    {
        $items = $this->catalog->getProducts($request);

        if ($request->response_type == 'paginated') {
            return $this->responsePagination(ProductResource::collection($items));
        } else {
            return $this->response(ProductResource::collection($items));
        }

    }

    public function getProductDetails(Request $request, $id): JsonResponse
    {
        $product = $this->catalog->getProductDetails($request, $id);
        if ($product) {
            $result['product'] = new ProductResource($product);
            if ($request->with_related_products == 'yes') {
                $result['related_products'] = ProductResource::collection($this->catalog->relatedProducts($product, $request));
            }
            return $this->response($result);
        } else {
            return $this->response(null);
        }

    }

    public function getAllBrands(Request $request)
    {
        $items = $this->brand->getAllBrands($request);
        return $this->response(BrandResource::collection($items));
    }
}
