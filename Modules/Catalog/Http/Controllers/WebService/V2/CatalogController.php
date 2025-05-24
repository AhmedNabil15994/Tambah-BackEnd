<?php

namespace Modules\Catalog\Http\Controllers\WebService\V2;

use Illuminate\Http\Request;
use Modules\Catalog\Transformers\WebService\PaginatedResource;
use Modules\Catalog\Transformers\WebService\V2\ProductResource;
use Modules\Catalog\Transformers\WebService\V2\CategoryResource;
use Modules\Catalog\Repositories\WebService\V2\CatalogRepository as Catalog;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Slider\Repositories\WebService\SliderRepository as Slider;
use Modules\Slider\Transformers\WebService\SliderResource;
use Illuminate\Http\JsonResponse;

class CatalogController extends WebServiceController
{
    protected $catalog;
    protected $slider;

    function __construct(Catalog $catalog, Slider $slider)
    {
        $this->catalog = $catalog;
        $this->slider = $slider;
    }

    function generateSku(Request $request){
        return $this->response(
            ["sku"=>generateBarcodeNumber($request->table ?? "products")]
        );
    }

    public function getHomeData(Request $request): JsonResponse
    {
        // Get Slider Data
        $sliders = $this->slider->getRandomPerRequest();
        $result['slider'] = SliderResource::collection($sliders);

        // Get Latest N Categories
        $categories = $this->catalog->getLatestNCategories($request);
        $result['categories'] = CategoryResource::collection($categories);
        return $this->response($result);
    }

    public function getAllCategories(Request $request): JsonResponse
    {
        $categories = $this->catalog->getAllCategories($request);
        return $this->response(CategoryResource::collection($categories));
    }

    public function getProductsByCategory(Request $request)
    {
        $categories = $this->catalog->getAllMainCategories($request);
        $result['main_categories'] = CategoryResource::collection($categories);

        /* $options = $this->catalog->getFilterOptions($request);
         $result['options'] = FilteredOptionsResource::collection($options);*/

        $products = $this->catalog->getProductsByCategory($request);
        $result['products'] = PaginatedResource::make($products)->mapInto(ProductResource::class);

        return $this->response($result);
    }

    public function getProductDetails(Request $request, $id): JsonResponse
    {
        $product = $this->catalog->getProductDetails($request, $id);
        if ($product) {
            $result = [
                'product' => new ProductResource($product),
                'related_products' => ProductResource::collection($this->catalog->relatedProducts($product)),
            ];
            return $this->response($result);
        } else
            return $this->response(null);
    }

}
