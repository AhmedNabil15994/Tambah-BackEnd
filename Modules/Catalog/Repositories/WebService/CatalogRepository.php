<?php

namespace Modules\Catalog\Repositories\WebService;

use Modules\Catalog\Entities\Age;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Traits\CatalogTrait;
use Modules\Core\Traits\CoreTrait;
use Modules\Variation\Entities\Option;
use Modules\Variation\Entities\ProductVariant;

class CatalogRepository
{
    use CatalogTrait, CoreTrait;

    protected $category;
    protected $prd;
    protected $prdVariant;
    protected $option;
    protected $age;

    public function __construct(
        Product $prd,
        Category $category,
        ProductVariant $prdVariant,
        Option $option,
        Age $age
    ) {
        $this->category = $category;
        $this->prd = $prd;
        $this->prdVariant = $prdVariant;
        $this->option = $option;
        $this->age = $age;
    }

    public function getLatestNCategories($request)
    {
        $categories = $this->buildCategoriesTree($request);
        $count = $request->categories_count ?? 8;
        return $categories->where('show_in_home', 1)->orderBy('sort', 'asc')->take($count)->get();
    }

    public function getCategories($request)
    {
        $query = $this->category->active()->mainCategories();

        if ($request->show_in_home == 1) {
            $query = $query->where('show_in_home', 1);
        }

        if ($request->model_flag == 'tree') {
            $query = $query->with('childrenRecursive');
        }

        $query = $query->whereHas('products', function ($query) use ($request) {
            $query->active();

            if ($request->with_featured_products == 'yes') {
                $query->where('featured', 1);
            }
        });

        $query = $query->orderBy('sort', 'asc');

        if ($request->response_type == 'paginated') {
            $query = $query->paginate($request->count ?? 24);
        } else {
            if (!empty($request->count)) {
                $query = $query->take($request->count);
            }

            $query = $query->get();
        }

        return $query;
    }

    public function getAllMainCategories($request)
    {
        return $this->category->active()->mainCategories()->orderBy('sort', 'asc')->get();
    }

    public function getFilterOptions($request)
    {
        return $this->option->active()
            ->with(['values' => function ($query) {
                $query->active();
            }])
            ->activeInFilter()
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getAutoCompleteProducts($request)
    {
        $products = $this->prd->active();
        if ($request['search']) {
            $products = $this->productSearch($products, $request);
        }
        return $products->orderBy('id', 'DESC')->get();
    }

    public function getAges($request)
    {
        $query = $this->age->active();
        $query = $query->whereHas('products', function ($query) {
            $query->active();
        });
        return $query->orderBy('id', 'DESC')->get();
    }

    public function getProducts($request)
    {
        $allCats = $this->getAllSubCategoryIds($request->category_id);
        array_push($allCats, intval($request->category_id));
        $optionsValues = isset($request->options_values) && !empty($request->options_values) ? array_values($request->options_values) : [];
        $optionsValues = $this->removeEmptyValuesFromArray($optionsValues);
        $tags = $this->removeEmptyValuesFromArray($request->tag_id ?? []);
        $ages = $this->removeEmptyValuesFromArray($request->age_id ?? []);
        $featured = $request->featured == 'yes' ? 1 : 0;

        $query = $this->prd->active()
            ->with([
                'offer' => function ($query) {
                    $query->active()->unexpired()->started();
                },
            ])
            ->with(['variants' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->active()->unexpired()->started();
                }]);
            }]);

        if ($request->category_id) {
            $query = $query->whereHas('categories', function ($query) use ($allCats) {
                $query->whereIn('product_categories.category_id', $allCats);
            });
        }

        if (!empty($tags)) {
            $query = $query->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('product_tags.tag_id', $tags);
            });
        }

        if (!empty($ages)) {
            $query = $query->whereHas('ages', function ($query) use ($ages) {
                $query->whereIn('product_ages.age_id', $ages);
            });
        }

        if (!empty($request->brand_id)) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->for_boys_girls) {
            $forBoysAndGirlsValues = [];
            if ($request->for_boys_girls == 'boys') {
                $forBoysAndGirlsValues = ['both', 'boys'];
            } elseif ($request->for_boys_girls == 'girls') {
                $forBoysAndGirlsValues = ['both', 'girls'];
            } elseif ($request->for_boys_girls == 'both') {
                $forBoysAndGirlsValues = ['both', 'boys', 'girls'];
            }
            $query->whereIn('for_boys_girls', $forBoysAndGirlsValues);
        }

        if (!is_null($request->featured)) {
            $query->where('featured', $featured);
        }

        if (count($optionsValues) > 0) {
            $query = $query->whereHas('variantValues', function ($query) use ($optionsValues) {
                $query->whereIn('option_value_id', $optionsValues);
            });
        }

        if ($request->get_offers == 'offers') {
            $query = $query->whereHas('offer', function ($query) {
                $query->active()->unexpired()->started();
            });
        } elseif ($request->get_offers == 'no_offers') {
            $query = $query->doesnthave('offer');
        }

        if ($request->is_new == 'yes') {
            $query = $query->where('is_new', 1);
        } elseif ($request->is_new == 'no') {
            $query = $query->where('is_new', 0);
        }

        if ($request['low_price'] && $request['high_price']) {
            $query = $query->whereBetween('price', [$request['low_price'], $request['high_price']]);
        }

        if ($request['search']) {
            $query = $this->productSearch($query, $request);
        }

        if ($request->with_random_data == 'yes') {
            $query = $query->inRandomOrder();
        } else {
            if ($request['sort']) {
                $query = $query->when($request['sort'] == 'a_to_z', function ($query) {
                    $query->orderBy('title->' . locale(), 'asc');
                })->when($request['sort'] == 'z_to_a', function ($query) {
                    $query->orderBy('title->' . locale(), 'desc');
                })->when($request['sort'] == 'low_to_high', function ($query) {
                    $query->orderBy('price', 'asc');
                })->when($request['sort'] == 'high_to_low', function ($query) {
                    $query->orderBy('price', 'desc');
                });
            } else {
                $query->orderBy('sort', 'DESC');
            }
        }

        if ($request->response_type == 'paginated') {
            $query = $query->paginate($request->count ?? 24);
        } else {
            if (!empty($request->count)) {
                $query = $query->take($request->count);
            }

            $query = $query->get();
        }

        return $query;
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

    public function findById($id)
    {
        $product = $this->prd->active();
        return $product->find($id);
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

    public function getAllSubCategoriesByParent($id)
    {
        return $this->category->where('category_id', $id)->get();
    }

    public function buildCategoriesTree($request)
    {
        $categories = $this->category->active()
            ->withCount(['products' => function ($q) {
                $q->active();
            }]);

        $categories = $categories->has('products');

        $categories = $categories->with(['adverts' => function ($query) use ($request) {
            $query->active()->unexpired()->started()->orderBy('sort', 'asc');
        }]);

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
                    $query->orderBy('products.sort', 'asc');
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
            'subCategories',
            'addOns',
            'variants' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->active()->unexpired()->started();
                }]);
            },
        ]);
    }

    public function relatedProducts($selectedProduct, $request = null)
    {
        $relatedCategoriesIds = $selectedProduct->categories()->pluck('product_categories.category_id')->toArray();
        $query = $this->prd->where('id', '<>', $selectedProduct->id)->active();
        $query = $query->whereHas('categories', function ($query) use ($relatedCategoriesIds) {
            $query->whereIn('product_categories.category_id', $relatedCategoriesIds);
        });

        $query = $query->orderBy('id', 'desc');

        if (!empty($request->related_products_count)) {
            $query = $query->take($request->related_products_count);
        }

        return $query->get();
    }

    public function getProductsByVendor($id)
    {
        $products = $this->prd->active();
        $products = $this->returnProductRelations($products, null);
        return $products->orderBy('id', 'DESC')->paginate(24);
    }
}
