<?php

namespace Modules\POS\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use Modules\Catalog\Transformers\WebService\PaginatedResource;
use Modules\POS\Transformers\POS\ProductResource;
use Modules\POS\Transformers\POS\CategoryResource;
use Modules\POS\Repositories\Cashier\CatalogRepository as Catalog;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Illuminate\Http\JsonResponse;

class CatalogController extends WebServiceController
{
    protected $catalog;

    function __construct(Catalog $catalog)
    {
        $this->catalog = $catalog;
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
