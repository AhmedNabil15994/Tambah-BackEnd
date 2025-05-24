<?php

namespace Modules\Catalog\Repositories\WebService\V2;

use Illuminate\Support\Facades\DB;
use Modules\Catalog\Entities\Category;
// use Modules\Catalog\Entities\VendorProduct;
use Modules\Catalog\Entities\Product;
use Modules\Variation\Entities\Option;
use Modules\Variation\Entities\ProductVariant;

class CatalogRepository
{
    protected $category;
    protected $product;
    // protected $vendor;
    protected $prd;
    protected $prdVariant;
    protected $option;

    public function __construct(
        Product $prd,
        Category $category,
        ProductVariant $prdVariant,
        Option $option
    ) {
        $this->category = $category;
        $this->prd = $prd;
        $this->prdVariant = $prdVariant;
        $this->option = $option;
    }

    public function getLatestNCategories($request)
    {
        $categories = $this->buildCategoriesTree($request);
        $count = $request->categories_count ?? 8;
        return $categories->where('show_in_home', 1)->orderBy('sort', 'asc')->take($count)->get();
    }

    public function getAllCategories($request)
    {
        $categories = $this->buildCategoriesTree($request);
        $categories = $categories->orderBy('sort', 'asc');
        if (!empty($request->categories_count)) {
            $categories = $categories->take($request->categories_count);
        }
        return $categories->get();
    }

    public function getAllMainCategories($request)
    {
        return $this->category->active()->mainCategories()->orderBy('sort', 'asc')->get();
    }

    public function getFilterOptions($request)
    {
        return $this->option->active()
            ->with([
                'values' => function ($query) {
                    $query->active();
                }
            ])
            ->activeInFilter()
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getProductsByCategory($request)
    {
        $optionsValues = isset($request->options_values) && !empty($request->options_values) ? array_values($request->options_values) : [];
        $products = $this->prd->active()
            ->with([
                'offer' => function ($query) {
                    $query->active()->unexpired()->started();
                },
            ])
            ->with([
                'variants' => function ($q) {
                    $q->with([
                        'offer' => function ($q) {
                            $q->active()->unexpired()->started();
                        }
                    ]);
                }
            ]);

        if (count($optionsValues) > 0) {
            $products = $products->whereHas('variantValues', function ($query) use ($optionsValues) {
                $query->whereIn('option_value_id', $optionsValues);
            });
        }

        if ($request->category_id) {
            $products->whereHas('categories', function ($query) use ($request) {
                $query->where('product_categories.category_id', $request->category_id);
            });
        }
        if ($request->branch_id) {
            $products->where("branch_id", $request->branch_id);
        }

        if ($request['low_price'] && $request['high_price']) {
            $products->whereBetween('price', [$request['low_price'], $request['high_price']]);
        }

        if ($request['search']) {
            $products = $this->productSearch($products, $request);
        }

        if ($request["display_type"]) {
            if ($request["display_type"] == "pos") {
                $products->showInPos();
            }
        }

        // dd($products->get()->toArray());
        return $products->orderBy('id', 'DESC')->paginate(24);
    }

    public function getProductDetails($request, $id)
    {
        $product = $this->prd->active();
        $product = $this->returnProductRelations($product, $request);
        return $product->find($id);
    }

    public function getLatestData($request)
    {
        $product = $this->prd->doesnthave('offer')->active();
        $product = $this->returnProductRelations($product, $request);

        if ($request['search']) {
            $product = $this->productSearch($product, $request);
        }

        return $product->orderBy('id', 'desc')->take(10)->get();
    }

    public function getOffersData($request)
    {
        $product = $this->prd->active();
        $product = $this->returnProductRelations($product, $request);

        if ($request['search']) {
            $product = $this->productSearch($product, $request);
        }

        $product = $product->whereHas('offer', function ($query) {
            $query->active()->unexpired()->started();
        });

        return $product->take(10)->get();
    }

    public function findOneProduct($id)
    {
        $product = $this->prd->active();
        $product = $this->returnProductRelations($product, null);
        return $product->find($id);
    }

    public function findOneProductSky($sku)
    {
        $product = $this->prd->active();

        $product = $this->returnProductRelations($product, null);

        return $product->where("sku", $sku)->first();
    }

    public function findOneProductVariant($id)
    {
        $product = $this->prdVariant->active()->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'productValues', 'product',
        ]);

        return $product->find($id);
    }

    public function findOneProductVariantSku($sku)
    {
        $product = $this->prdVariant->active()->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'productValues', 'product',
        ]);

        return $product->where("sku", $sku)->first();
    }


    public function getAllSubCategoriesByParent($id)
    {
        return $this->category->where('category_id', $id)->get();
    }

    public function buildCategoriesTree($request)
    {
        $categories = $this->category->active()
            ->withCount([
                'products' => function ($q) {
                    $q->active();
                }
            ]);

        if ($request->with_sub_categories == 'yes') {
            $categories = $categories->with('childrenRecursive');
        }

        if ($request->get_main_categories == 'yes') {
            $categories = $categories->mainCategories();
        }

        if ($request->with_products == 'yes') {
            // Get Main Category Products
            $categories = $categories->with([
                'products' => function ($query) use ($request) {
                    $query->active();
                    $query = $this->returnProductRelations($query, $request);

                    if ($request['search']) {
                        $query = $this->productSearch($query, $request);
                    }

                    $query->orderBy('products.id', 'DESC');
                },
            ]);
        }

        return $categories;
    }

    public function productSearch($model, $request)
    {
        $term = strtolower($request['search']);
        return $model->where(function ($query) use ($term) {
            $query->whereRaw('lower(sku) like (?)', ["%{$term}%"]);
            $query->orWhereRaw('lower(title) like (?)', ["%{$term}%"]);
            $query->orWhereRaw('lower(slug) like (?)', ["%{$term}%"]);
        });
    }

    public function returnProductRelations($model, $request)
    {
        return $model->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'options',
            'images',
            // 'vendor',
            'subCategories',
            'addOns',
            'variants' => function ($q) {
                $q->with([
                    'offer' => function ($q) {
                        $q->active()->unexpired()->started();
                    }
                ]);
            },
        ]);
    }

    public function relatedProducts($selectedProduct)
    {
        $relatedCategoriesIds = $selectedProduct->categories()->pluck('product_categories.category_id')->toArray();
        $products = $this->prd->where('id', '<>', $selectedProduct->id)->active();
        $products = $products->whereHas('categories', function ($query) use ($relatedCategoriesIds) {
            $query->whereIn('product_categories.category_id', $relatedCategoriesIds);
        });
        return $products->orderBy('id', 'desc')->take(10)->get();
    }

    public function getProductsByVendor($id)
    {
        $products = $this->prd->active()->where('vendor_id', $id);
        $products = $this->returnProductRelations($products, null);
        return $products->orderBy('id', 'DESC')->paginate(24);
    }
}
