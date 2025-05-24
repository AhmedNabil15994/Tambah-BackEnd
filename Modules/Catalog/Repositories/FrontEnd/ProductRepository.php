<?php

namespace Modules\Catalog\Repositories\FrontEnd;

use Modules\Catalog\Entities\Product;
use Illuminate\Support\Arr;
use DB;
use Modules\Variation\Entities\Option;
use Modules\Variation\Entities\OptionValue;
use Modules\Variation\Entities\ProductVariant;
use Modules\Variation\Entities\ProductVariantValue;

class ProductRepository
{
    protected $product;
    protected $variantPrd;
    protected $variantPrdValue;
    protected $option;
    protected $optionValue;

    public function __construct(
        Product $product,
        ProductVariant $variantPrd,
        ProductVariantValue $variantPrdValue,
        Option $option,
        OptionValue $optionValue
    ) {
        $this->product = $product;
        $this->variantPrd = $variantPrd;
        $this->variantPrdValue = $variantPrdValue;
        $this->option = $option;
        $this->optionValue = $optionValue;
    }

    public function findBySlug($slug)
    {
        $product = $this->product->active()
            ->with([
                "categories",
                "images",
                "tags",
                "options.option",
                'offer' => function ($query) {
                    $query->active()->unexpired()->started();
                },
                'addOns' => function ($q) {
                    $q->with('addOnOptions');
                },
            ]);


        /* $product = $product->where(function ($query) {
            $query->doesnthave('variants')->orWhereHas('variants', function ($query) {
                $query->active();
            });
        }); */

        $product = $product
            ->AnyTranslation('slug', $slug)
            ->first();

        return $product;
    }

    public function getAllCount()
    {
        return $this->product
            ->count();
    }

    static function findProductById($id)
    {
        $product = Product::with([
            "categories",
            "images",
            "tags",
            "options.option",
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'addOns' => function ($q) {
                $q->with('addOnOptions');
            }
        ]);

        $product = $product->find($id);
        return $product;
    }

    public function checkRouteLocale($model, $slug)
    {
        // if ($model->translate()->where('slug', $slug)->first()->locale != locale())
        //     return false;
        if ($array = $model->getTranslations("slug")) {
            $locale = array_search($slug, $array);

            return $locale == locale();
        }
        return true;
    }

    public function autoCompleteSearch($request)
    {
        return $this->product->active()->where(function ($query) use ($request) {
            $query->where(function ($query) use ($request) {

                foreach (config('translatable.locales') as $code) {
                    $query->orWhere('title->' . $code, 'like', '%' . $request->input('query') . '%');
                    $query->orWhere('slug->' . $code, 'like', '%' . $request->input('query') . '%');
                }
            })->orWhereHas('searchKeywords', function ($query) use ($request) {

                foreach (config('translatable.locales') as $code) {
                    $query->orWhere('title->' . $code, 'like', '%' . $request->input('query') . '%');
                }
            });
        });
    }

    public function getProductsByCategory($request, $category, $response_type = 'paginate')
    {
        $products = $this->product->orderBy('id', 'desc')->active()->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            }
        ]);


        $products = $products->where(function ($q) use ($request, $category) {

            if ((isset($request['categories']) && count($request['categories'])) || $category) {
                $q->whereHas('categories', function ($query) use ($request, $category) {

                    if (isset($request['categories']) && count($request['categories'])) {
                        $query->whereIn('product_categories.category_id', (array) $request['categories']);
                    } elseif ($category != null) {
                        $query->where('product_categories.category_id', $category->id);
                    }
                });
            }
        });

        if (isset($request->s) && !empty($request->s)) {

            $products = $products->where(function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('title->' . locale(), 'like', '%' . $request->s . '%');
                    $query->orWhere('slug->' . locale(), 'like', '%' . $request->s . '%');
                })->orWhereHas('searchKeywords', function ($query) use ($request) {
                    $query->where('title->' . locale(), 'like', '%' . $request->s . '%');
                });
            });
        }

        if (isset($request['tags']) && count($request['tags'])) {
            foreach ((array) $request['tags'] as $tag) {
                $products = $products->whereHas('tags', function ($query) use ($tag) {
                    $query->where('slug->' . locale(), $tag);
                });
            }
        }
        if (
            isset($request['price_from']) && $request['price_from'] &&
            isset($request['price_to']) && $request['price_to']
        ) {

            $products = $products->whereBetween('price', [$request['price_from'], $request['price_to']]);
        }

        $products = $products->where(function ($query) use ($request) {
            $query->doesnthave('variants')->orWhereHas('variants', function ($query) use ($request) {
                $query->active();
            });
        });

        $products->withCount(['variants']);

        if ($response_type == 'paginate') {

            $products = $products->paginate(12, \Modules\Catalog\Constants\Product::SINGLE_PRODUCT_COLS_NEEDS);
        }

        return $products;
    }

    public function getRelatedProducts($product, $categories)
    {
        $products = $this->product->orderBy('id', 'desc')->active()
            ->with([
                'offer' => function ($query) {
                    $query->active()->unexpired()->started();
                }
            ])
            ->where('id', '<>', $product->id)
            ->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('product_categories.category_id', $categories);
            });

        $products = $products->withCount(['variants'])->get();

        return $products;
    }

    public function getRelatedProductsV2($product)
    {
        $products = $this->product->orderBy('sort', 'asc')->active()
            ->with([
                'offer' => function ($query) {
                    $query->active()->unexpired()->started();
                }
            ])
            ->where('id', '<>', $product->id)
            ->whereIn('id', $product->related_products ?? []);

        $products = $products->get();
        return $products;
    }

    public function getRelatedProductsWithBrand($product)
    {
        $products = $this->product->orderBy('sort', 'asc')->active()
            ->with([
                'offer' => function ($query) {
                    $query->active()->unexpired()->started();
                }
            ])
            ->where('id', '<>', $product->id)
            ->whereHas('tags', function ($query) use ($product) {
                $query->where('product_tags.product_id', $product->id);
            });

        $products = $products->take(15)->get();

        return $products;
    }

    public function getLatestProducts()
    {
        $products = $this->product->active()
            ->with([
                'offer' => function ($query) {
                    $query->active()->unexpired()->started();
                }
            ])->latest();

        $products = $products->take(15)->get();

        return $products;
    }

    public function findOneProduct($id)
    {
        $product = $this->product->active();

        $product = $this->returnProductRelations($product, null);

        return $product->find($id);
    }

    public function findOneProductVariant($id)
    {
        $product = $this->variantPrd->active()->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'productValues', 'product',
        ]);

        return $product->find($id);
    }

    public function findById($id)
    {
        $product = $this->product->withDeleted()
            ->with([
                'tags', 'images',
                'addOns' => function ($q) {
                    $q->with('addOnOptions');
                },
                'options.option' => function ($q) {
                    $q->active()->with([
                        'values' => function ($query) {
                            $query->active();
                        }
                    ]);
                }
            ]);

        return $product->find($id);
    }

    public function findVariantProductById($id)
    {
        $product = $this->variantPrd->with([
            'product', 'offer', 'productValues' => function ($q) {
                $q->with([
                    'optionValue', 'productOption' => function ($q) {
                        $q->with('option');
                    }
                ]);
            }
        ]);

        return $product->find($id);
    }

    public function getVariantProductsByPrdId($id)
    {
        $products = $this->variantPrd->with([
            'offer', 'productValues' => function ($q) {
                $q->with([
                    'optionValue', 'productOption' => function ($q) {
                        $q->with('option');
                    }
                ]);
            }
        ])->where('product_id', $id)->where('status', true);

        return $products->get();
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
                $q->with([
                    'offer' => function ($q) {
                        $q->active()->unexpired()->started();
                    }
                ]);
            },
        ]);
    }
}
