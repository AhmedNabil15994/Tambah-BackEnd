<?php

namespace Modules\Catalog\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;
use Modules\Catalog\Repositories\FrontEnd\CategoryRepository as Category;
use Modules\Tags\Repositories\FrontEnd\TagsRepository as Tags;

class CategoryController extends Controller
{
    protected $product;
    protected $category;
    protected $tags;

    function __construct(Product $product, Category $category, Tags $tags)
    {
        $this->product = $product;
        $this->category = $category;
        $this->tags = $tags;
    }

    public function index(Request $request)
    {
        abort(404);
        return view('catalog::frontend.categories.category-products');
    }

    public function categoryChildren(Request $request, $slug = null)
    {
        if ($slug) {

            $parent = $this->category->findBySlug($slug);

            if (!$parent) {
                abort(404);
            }
        } else {

            $parent = null;
        }

        if ($parent && !checkRouteLocale($parent, $slug)) {

            return redirect()->route('frontend.categories.children', [
                $parent->slug
            ]);
        }

        $categories = $parent->children()->active()->paginate(12);

        return view('catalog::frontend.categories.index', compact('parent', 'categories'));
    }

    public function productsCategory(Request $request, $slug = null)
    {
        if ($slug) {

            $category = $this->category->findBySlug($slug);

            if (!$category) {
                abort(404);
            }
        } else {

            $category = null;
        }

        $products = $this->product->getProductsByCategory($request, $category, 'query');
        $products_count = $products;
        $products_count = $products_count->count();
        if ($products_count) {
            $products = $products->paginate(12,\Modules\Catalog\Constants\Product::SINGLE_PRODUCT_COLS_NEEDS);
        }else{
            $products = [];
        }

        $products = view('catalog::frontend.products.components.product-builder', compact('products', 'category'))->render();
        $tags = $this->tags->getAllActive();
        $count_all_products = $this->product->getAllCount();
        $categories = $this->category->getAllMainActive($order = 'sort', $sort = 'asc', [
            'children' => function($query) {
                $query->active()->has('products');
                // $query->select('id', 'title', 'slug');
            }
        ], ['activeProducts']);

        if ($category && !checkRouteLocale($category, $slug)) {

            return redirect()->route('frontend.categories.products', [
                $category->slug
            ]);
        }

        return view('catalog::frontend.categories.category-products',
            compact('tags', 'categories', 'category', 'count_all_products','products_count','products'));
    }


}
