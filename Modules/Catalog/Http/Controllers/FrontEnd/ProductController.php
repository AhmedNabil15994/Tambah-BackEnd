<?php

namespace Modules\Catalog\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;

class ProductController extends Controller
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index(Request $request, $slug, $category = null)
    {
        $product = $this->product->findBySlug($slug);
        if (!$product) {
            abort(404);
        }

        if ($product && !checkRouteLocale($product, $slug)) {

            return redirect()->route('frontend.products.index', [
                $product->slug,
            ]);
        }

        $variantPrd = null;
        $productTitle = $product->title;
        $selectedOptions = [];
        $selectedOptionsValue = [];

        if (count($request->query()) > 0) {

            $selectedOptions = getOptionsAndValuesIds($request)['selectedOptions'];
            $selectedOptionsValue = getOptionsAndValuesIds($request)['selectedOptionsValue'];

            if ($request->has('var') && !empty($request->var) && !in_array("", $selectedOptions) && !in_array("", $selectedOptionsValue)) {
                $variantPrd = $this->product->findVariantProductById($request->var);
                $variantPrd->image = $variantPrd->image ? url($variantPrd->image) : null;
                $productTitle = generateVariantProductData($product, $variantPrd->id, $selectedOptionsValue)['name'];
            }
        }

        // $related_products = $this->product->getRelatedProducts($product, $product->categories->pluck('id')->toArray());
        $related_products = $this->product->getRelatedProductsV2($product);
        $product_with_brand = $this->product->getRelatedProductsWithBrand($product);
        $latest_products = $this->product->getLatestProducts();

        if (is_null($product->variants) || count($product->variants) == 0) {
            $formDataId = $product->id;
            $formAction = route('frontend.shopping-cart.create-or-update', [$product->slug]);
        } else {
            $formDataId = $variantPrd->id ?? null;
            $formAction = route('frontend.shopping-cart.create-or-update', [$product->slug, $variantPrd->id ?? null]);
        }

        return view('catalog::frontend.products.index', compact(
            'product',
            'related_products',
            'product_with_brand',
            'latest_products',
            'variantPrd',
            'productTitle',
            'selectedOptions',
            'selectedOptionsValue',
            'formDataId',
            'formAction'
        ));

    }

    public function getPrdVariationInfo(Request $request)
    {
        $variantObject = [];
        $product = $this->product->findById($request->product_id);

        if (!$product) {
            return response()->json(["errors" => __('catalog::frontend.products.product_not_found')], 422);
        }

        if (count($request->selectedOptions) > 0 && count($request->selectedOptionsValue) > 0 && !in_array("", $request->selectedOptionsValue)) {

            $variantProducts = $this->product->getVariantProductsByPrdId($request->product_id);
            foreach ($variantProducts as $k => $val) {
                $values = $val->productValues()->pluck('option_value_id')->toArray();
                $result = array_diff($values, $request->selectedOptionsValue);

                if (count($result) == 0) {
                    $variantObject = $val;
                    $variantObject->image = $val->image ? url($val->image) : null;
                }
            }

        }

        if (!empty($variantObject)) {
            $productTitle = generateVariantProductData($product, $variantObject->id, $request->selectedOptionsValue)['name'];
            $data = [
                'variantProduct' => $variantObject,
                'productTitle' => $productTitle,
                'formAction' => route('frontend.shopping-cart.create-or-update',
                    [$product->slug, $variantObject->id]),
                'data_id' => $variantObject->id,
                'form_view' => view('catalog::frontend.products._variant_add_to_cart_form')->with([
                    'product' => $product,
                    'productTitle' => $productTitle,
                    'variantProduct' => $variantObject,
                    'selectedOptions' => $request->selectedOptions,
                    'selectedOptionsValue' => $request->selectedOptionsValue,
                ])->render(),
            ];
            return response()->json(["message" => 'Success', "data" => $data], 200);
        } else {
            return response()->json(["errors" => __('catalog::frontend.products.validation.variation_not_found')], 423);
        }

    }

}
