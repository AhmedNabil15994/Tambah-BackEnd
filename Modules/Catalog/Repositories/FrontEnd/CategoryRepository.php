<?php

namespace Modules\Catalog\Repositories\FrontEnd;

use Modules\Catalog\Entities\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Modules\Catalog\Entities\Product;

class CategoryRepository
{
    protected $category;
    protected $prd;

    public function __construct(Category $category, Product $prd)
    {
        $this->category = $category;
        $this->prd = $prd;
    }

    public function getHeaderCategories($order = 'sort', $sort = 'asc', $with=["children"])
    {

        return $this->category->has('products')
            ->active()
            ->orderBy($order, $sort)
            ->whereNull('category_id')
            ->with([
                'children' => function($query) {
                    $query->active()->has('products');
                }
            ])
            ->get();
    }

    public function getAllActive($order = 'sort', $sort = 'asc', $with=[])
    {
        // get all categories that have only active vendor products
        return $this->category->has('products')
            ->active()
            ->with($with)
            ->orderBy($order, $sort)
//            ->where('id', '<>', '1')
            ->get();
    }

    public function getAllMainActive($order = 'sort', $sort = 'asc', $with=[], $withCount=[])
    {


        // get all categories that have only active vendor products
        return $this->category->has('products')->MainCategories()
            ->active()
            ->with($with)->withCount($withCount)
            ->orderBy($order, $sort)
//            ->where('id', '<>', '1')
            ->get(['id','title','slug']);
    }

    public function mainCategoriesOfVendorProducts($vendor, $request = null)
    {
        $categories = $this->category->mainCategories()
            ->with([
                'products' => function ($query) use ($vendor, $request) {
                    if (isset($request['search'])) {
                        $query->where('description', 'like', '%' . $request['search'] . '%');
                        $query->orWhere('short_description', 'like', '%' . $request['search'] . '%');
                        $query->orWhere('title', 'like', '%' . $request['search'] . '%');
                        $query->orWhere('slug', 'like', '%' . $request['search'] . '%');
                    }

                    if (isset($request['sorted_by'])) {
                        if ($request['sorted_by'] == 'a_to_z') {
                            $query->orderBy('title->'.locale(), 'ASC');
                        }

                        if ($request['sorted_by'] == 'latest') {
                            $query->orderBy('id', 'ASC');
                        }
                    } else {
                        $query->orderBy('id', 'ASC');
                    }

                    $query->with([
                        'addOns',
                        'offer' => function ($query) {
                            $query->active()->unexpired()->started();
                        },
                    ]);/*->active();*/
                }
            ])
            ->active()
            ->orderBy('sort', 'ASC')
            ->get();

        return $categories;
    }

    public function findBySlug($slug)
    {
        return $this->category
            ->active()
            ->AnyTranslation('slug',$slug)->first();
    }

    public function checkRouteLocale($model, $slug)
    {
        if ($array = $model->getTranslations("slug")) {
            $locale = array_search($slug, $array);

            return $locale == locale();
        }

        return true;
    }

    public function getFeaturedProducts($request, $with=[])
    {
        $product = $this->prd->with( 'tags');
        $product = $product->where('featured', '1');

        $product = $product->doesnthave('offer')->orderBy('id', 'desc')->active()
            ->with($with);
        return $product->take(10)->get();
    }

    public function getLatestOffersData($request)
    {
        $product = $this->prd->with( 'tags');

        $product = $product->active()->whereHas('offer', function ($query) {
            $query->active()->unexpired()->started();
        });
        return $product->take(10)->get();
    }

    public function getMainCategoriesData($request, $with=[])
    {
        return $this->category->mainCategories()
            ->has('products')
            ->active()
//            ->where('id', '<>', '1')
            ->where('show_in_home', '1')
            ->with($with)
            ->orderBy('sort', 'ASC')
//            ->take(5)
            ->get();
    }

    public function getMostSellingProducts($request)
    {
        $sales = DB::table('products')
            ->rightJoin('order_products', 'products.id', '=', 'order_products.product_id')
            ->selectRaw('products.*, COALESCE(sum(order_products.qty),0) totalQuantity')
            ->groupBy('products.id');

        $result = DB::table('products')
            ->rightJoin('product_variants', function ($join) {
                $join->on('products.id', '=', 'product_variants.product_id');
                $join->join('order_variant_products', function ($join) {
                    $join->on('product_variants.id', '=', 'order_variant_products.product_variant_id');
                });
            })
            ->selectRaw('products.*, COALESCE(sum(order_variant_products.qty),0) totalQuantity')
            ->groupBy('products.id')
            ->union($sales)
            ->orderBy('totalQuantity', 'desc')
            ->take(20)
            ->get();

        return $result;
    }
}
