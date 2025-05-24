<?php

namespace Modules\POS\Repositories\Main;

use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\OrderProduct;
use Modules\Order\Entities\OrderRefundItem;
use Modules\Order\Entities\OrderVariantProduct;

abstract class ReportRepository
{
    public function __construct()
    {
    }

    public function productSales($request, $vendors)
    {
        $type = "";
        if (isset($request['req']['type']) && $request['req']['type'] != '') {
            $type = $request['req']['type'];
        }
        $variatint = DB::table("order_variant_products")->
        select(
            \DB::raw(
                "product_translations.title as title  ,
                                    products.id  as vendor_id    ,
                                    products.qty  as product_stock    ,
                                    \"variant\" as type ,
                                    order_variant_products.id  as id ,
                                    order_variant_products.qty  as qty ,
                                    order_variant_products.total  as total ,
                                    order_variant_products.price  as price ,
                                    order_variant_products.sale_price  as sale_price ,
                                    order_variant_products.original_total  as original_total ,
                                    order_variant_products.product_variant_id  as product_variant_id ,
                                    order_variant_products.total_profit  as total_profit ,
                                    order_variant_products.created_at  as created_at  ,
                                    order_variant_products.order_id as order_id ,
                                    transactions.method as method ,
                                    product_variants.sku  as sku ,
                                    branch_translations.title as branch ,
                                    vendor_translations.title as vendor_title
                            "
            )
        )
            ->join("product_variants", "order_variant_products.product_variant_id", "=", "product_variants.id")
            // ->join("orders","order_variant_products.order_id", "=", "orders.id")
            ->join("transactions", function ($join) use ($request) {
                $join->on("order_variant_products.order_id", "=",'transactions.transaction_id')
                ->where("transactions.transaction_type","=", Order::class);
            })
            ->join("products", "products.id", "=", "product_variants.product_id")
            ->join("branches", "products.branch_id", "=", "branches.id")
            ->join("vendors", "products.vendor_id", "=", "vendors.id")
            ->join("product_translations", function ($join) use ($request) {
                $join->on("products.id", "=", "product_translations.product_id")
                    ->where("product_translations.locale", locale());
            })
            ->join("branch_translations", function ($join) use ($request) {
                $join->on("branches.id", "=", "branch_translations.branch_id")
                    ->where("branch_translations.locale", locale());
            })
            ->join("vendor_translations", function ($join) use ($request) {
                $join->on("vendors.id", "=", "vendor_translations.vendor_id")
                    ->where("vendor_translations.locale", locale());
            })
            ->where("order_variant_products.is_refund", false);

        $variatint = $this->filterDateTableDate($variatint, "order_variant_products", $request);
        $variatint = $this->filterDateTableVendor($variatint, "products.vendor_id", $request, $vendors);
        $variatint = $this->searchBranchIdDatatable($variatint, $request,'products.branch_id');
        $variatint = $this->searchProductNameDaTable($variatint, $request);

        if ($type == "variant") {
            return $variatint;
        }

        $products = DB::table("order_products")->
        select(
            \DB::raw(
                "product_translations.title as title  ,
                                    products.id  as vendor_id    ,
                                    products.qty  as product_stock    ,
                                    \"product\" as type ,
                                    order_products.id  as id ,
                                    order_products.qty  as qty ,
                                    order_products.total  as total ,
                                    order_products.price  as price ,
                                    order_products.sale_price  as sale_price ,
                                    order_products.original_total  as original_total ,
                                    null as product_variant_id ,
                                    order_products.total_profit  as total_profit ,
                                    order_products.created_at  as created_at  ,
                                    order_products.order_id as order_id ,
                                    transactions.method as method ,
                                    products.sku  as sku ,
                                    branch_translations.title as branch ,
                                    vendor_translations.title as vendor_title
                            "
            )
        )
            ->join("products", "products.id", "=", "order_products.product_id")
            ->join("transactions", function ($join) use ($request) {
                $join->on("order_products.order_id", "=",'transactions.transaction_id')
                ->where("transactions.transaction_type","=", Order::class);
            })
            ->join("branches", "products.branch_id", "=", "branches.id")
            ->join("vendors", "products.vendor_id", "=", "vendors.id")
            ->join("product_translations", function ($join) use ($request) {
                $join->on("products.id", "=", "product_translations.product_id")
                    ->where("product_translations.locale", locale());
            })
            ->join("branch_translations", function ($join) use ($request) {
                $join->on("branches.id", "=", "branch_translations.branch_id")
                    ->where("branch_translations.locale", locale());
            })
            ->join("vendor_translations", function ($join) use ($request) {
                $join->on("vendors.id", "=", "vendor_translations.vendor_id")
                    ->where("vendor_translations.locale", locale());
            })
            ->where("order_products.is_refund", false);

        $products = $this->filterDateTableDate($products, "order_products", $request);
        $products = $this->filterDateTableVendor($products, "products.vendor_id", $request, $vendors);
        $products = $this->searchBranchIdDatatable($products, $request,'products.branch_id');
        $products = $this->searchProductNameDaTable($products, $request);
        
        $products->when($type == "", function ($query) use ($variatint) {
            $query->union($variatint);
        });
        
        return $products;
    }

    public function productRefunSql($request)
    {
        $type = "";
        if (isset($request['req']['type']) && $request['req']['type'] != '') {
            $type = $request['req']['type'];
        }
        $variatint = DB::table("order_refund_items")->
        select(
            \DB::raw(
                "product_translations.title as title  ,
                                    \"variant\" as type ,
                                    order_refund_items.id  as id ,
                                    order_refund_items.qty  as qty ,
                                    order_refund_items.total  as total ,
                                    order_variant_products.price  as price ,
                                    order_variant_products.product_variant_id  as product_variant_id ,
                                    order_variant_products.created_at  as created_at  ,
                                    order_variant_products.order_id as order_id ,
                                    product_variants.sku  as sku ,
                                    vendor_translations.title as vendor_title
                            "
            )
        )
            ->join("order_variant_products", function ($join) {
                $join->on("order_variant_products.id", "=", "item_id")
                    ->where("item_type", OrderVariantProduct::class);
            })
            ->join("product_variants", "order_variant_products.product_variant_id", "=", "product_variants.id")
            // ->join("orders","order_variant_products.order_id", "=", "orders.id")
            ->join("products", "products.id", "=", "product_variants.product_id")
            ->join("vendors", "products.vendor_id", "=", "vendors.id")
            ->join("product_translations", function ($join) use ($request) {
                $join->on("products.id", "=", "product_translations.product_id")
                    ->where("product_translations.locale", locale());
            })
            ->join("vendor_translations", function ($join) use ($request) {
                $join->on("vendors.id", "=", "vendor_translations.vendor_id")
                    ->where("vendor_translations.locale", locale());
            });

        $variatint = $this->filterDateTableDate($variatint, "order_refund_items", $request);
        $variatint = $this->filterDateTableVendor($variatint, "products.vendor_id", $request);
        $variatint = $this->searchProductNameDaTable($variatint, $request);



        if ($type == "variant") {
            return $variatint;
        }

        $products = DB::table("order_refund_items")->
        select(
            \DB::raw(
                "product_translations.title as title  ,
                                    \"product\" as type ,
                                    null as product_variant_id ,
                                    order_refund_items.id  as id ,
                                    order_refund_items.qty  as qty ,
                                    order_refund_items.total  as total ,
                                    order_products.price  as price ,
                                    order_products.created_at  as created_at  ,
                                    order_products.order_id as order_id ,
                                    products.sku  as sku ,
                                    vendor_translations.title as vendor_title
                            "
            )
        )
            ->join("order_products", function ($join) {
                $join->on("order_products.id", "=", "item_id")
                    ->where("item_type", OrderProduct::class);
            })
            ->join("products", "products.id", "=", "order_products.product_id")
            ->join("vendors", "products.vendor_id", "=", "vendors.id")
            ->join("product_translations", function ($join) use ($request) {
                $join->on("products.id", "=", "product_translations.product_id")
                    ->where("product_translations.locale", locale());
            })
            ->join("vendor_translations", function ($join) use ($request) {
                $join->on("vendors.id", "=", "vendor_translations.vendor_id")
                    ->where("vendor_translations.locale", locale());
            });

        $products = $this->filterDateTableDate($products, "order_products", $request);
        $products = $this->filterDateTableVendor($products, "products.vendor_id", $request);
        $products = $this->searchProductNameDaTable($products, $request);
        $products->when($type == "", function ($query) use ($variatint) {
            $query->union($variatint);
        });
        return $products;
    }


    public function filterDateTableDate($query, $table, $request)
    {
        // Search Categories by Created Dates
        if (isset($request['req']['from']) && $request['req']['from'] != '') {
            $query->whereDate($table.'.created_at', '>=', $request['req']['from']);
        }

        if (isset($request['req']['to']) && $request['req']['to'] != '') {
            $query->whereDate($table.'.created_at', '<=', $request['req']['to']);
        }

        return $query;
    }


    public function searchProductNameDaTable($query, $request)
    {
        // Search Categories by Created Dates
        $query->when($request->input('search.value'), function ($query) use ($request) {
            $query->whereExists(function ($query) use ($request) {
                $query->select(DB::raw(1))
                    ->where('product_translations.title', 'like', '%'.$request->input('search.value').'%')
                    ->from('product_translations')
                    ->whereColumn('products.id', 'product_translations.product_id');
            });
        });


        return $query;
    }

    public function searchBranchIdDatatable($query, $request, $colum = "orders.branch_id")
    {
        // Search Categories by Created Dates
        if (isset($request['req']['branch_id']) && $request['req']['branch_id'] != '') {
            $query->where($colum, '=', $request['req']['branch_id']);
        }
        return $query;
    }

    public function searchFromCashierDatatable($query, $request, $colum = "orders.from_cashier")
    {
        // Search Categories by Created Dates
        if (isset($request['req']['from_cashier']) && $request['req']['from_cashier'] != '') {
            $query->where($colum, '=', $request['req']['from_cashier']);
        }
        return $query;
    }

    public function searchVendorNameDaTable($query, $request)
    {
        // Search Categories by Created Dates
        $query->when($request->input('search.value'), function ($query) use ($request) {
            $query->whereExists(function ($query) use ($request) {
                $query->select(DB::raw(1))
                    ->where('vendor_translations.title', 'like', '%'.$request->input('search.value').'%')
                    ->from('vendor_translations')
                    ->whereColumn('vendors.id', 'vendor_translations.vendor_id');
            });
        });


        return $query;
    }

    public function filterDateTableVendor($query, $columWithTable, $request, $vendors = 'all')
    {
        if ($vendors != 'all') {
            $query->whereIn($columWithTable, (array) $vendors);
        }
        // Search Categories by Created Dates
        if (isset($request['req']['vendor_id']) && $request['req']['vendor_id'] != '') {
            $query->where($columWithTable, '=', $request['req']['vendor_id']);
        }


        return $query;
    }

    public function refundSales($request, $vendors)
    {

        $refund = OrderRefundItem::whereHasMorph("item", ["*"])->with([
            "item" => function ($morphTo) {
                $morphTo->morphWith([OrderProduct::class => ['product.vendor', "product.branch"]])
                    ->morphWith([OrderVariantProduct::class => ['variant.product.vendor', "variant.product.branch"]]);
            }

        ])->where(function ($query) use ($vendors) {
            if ($vendors != 'all') {
                $query
                    ->whereHasMorph("item", [OrderProduct::class], function ($query) use ($vendors) {
                        $query->whereHas("product", function ($query) use ($vendors) {
                            $query->whereIn("vendor_id", (array) $vendors);
                        });
                    })
                    ->orWhereHasMorph("item", [OrderVariantProduct::class], function ($query) use ($vendors) {
                        $query->whereHas("variant.product", function ($query) use ($vendors) {
                            $query->whereIn("vendor_id", (array) $vendors);
                        });
                    });
            }

        })
            ->
            when($request->input('req.vendor_id'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
                        $query
                            ->whereHasMorph("item", [OrderProduct::class], function ($query) use ($request) {
                                $query->whereHas("product", function ($query) use ($request) {
                                    $query->where("vendor_id", $request->input('req.vendor_id'));
                                });
                            })
                            ->orWhereHasMorph("item", [OrderVariantProduct::class], function ($query) use ($request) {
                                $query->whereHas("variant.product", function ($query) use ($request) {
                                    $query->where("vendor_id", $request->input('req.vendor_id'));
                                });
                            });;
                    });
                });
            })
            ->when($request->input('search.value'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
                        $query
                            ->whereHasMorph("item", [OrderProduct::class], function ($query) use ($request) {
                                $query->whereHas("product", function ($query) use ($request) {
                                    $query->where("order_id", 'like', '%'.$request->input('search.value').'%');
                                })
                                    ->orWhereHas("product.translations", function ($query) use ($request) {
                                        $query->where("title", 'like', '%'.$request->input('search.value').'%');
                                    });
                            })
                            ->orWhereHasMorph("item", [OrderVariantProduct::class], function ($query) use ($request) {
                                $query->whereHas("variant.product", function ($query) use ($request) {
                                    $query->where("order_id", 'like', '%'.$request->input('search.value').'%');
                                })
                                    ->orWhereHas("variant.product.translations", function ($query) use ($request) {
                                        $query->where("title", 'like', '%'.$request->input('search.value').'%');
                                    });
                            });;
                    });
                });
            })
            ->when($request->input('req.type'), function ($query) use ($request) {
                $request->input('req.type') == "product" ? $query->orderProduct() : $query->orderVariantProduct();
            });

        $refund = $this->filterDateTableDate($refund, "order_refund_items", $request);
        return $refund;
    }


    public function orderSales($request)
    {
        $order = Order::whereHas('orderStatus', function ($query) {
            $query->successOrderStatus();
        })
            ->with(["user", "cashier", "transactions"])
            ->withCount(["vendors", "orderProducts", "orderVariations"])
            ->when($request->input('search.value'), function ($query) use ($request) {
                $query->where('orders.id', 'like', '%'.$request->input('search.value').'%');
            })
            ->when($request->input('req.vendor_id'), function ($query) use ($request) {
                $query->whereHas("vendors", function ($query) use ($request) {
                    $query->where("vendors.id", $request->input('req.vendor_id'));
                });
            })
            ->when($request->input('req.cashier_id'), function ($query) use ($request) {
                $query->where("cashier_id", $request->input('req.cashier_id'));
            });
            
        $order = $this->filterDateTableDate($order, "orders", $request);
        $order = $this->searchBranchIdDatatable($order, $request);
        $order = $this->searchFromCashierDatatable($order, $request);

        $order->latest();



        return $order;
    }

    public function orderSalesSql($request, $vendors)
    {
        $order = DB::table("orders")
            ->select(
                DB::raw(
                    "
                                orders.total            as total ,
                                orders.shipping         as shipping ,
                                ( orders.original_subtotal - orders.subtotal ) as discount ,
                                orders.subtotal         as subtotal ,
                                orders.subtotal_type    as subtotal_type ,
                                transactions.method     as transactions_id ,
                                users.name              as user_id ,
                                branch_translations.title as branch ,
                                cashiers.name           as cashier_id        ,
                                orders.created_at       as created_at , 
                                orders.id               as id  ,
                                orders.extra_attributes as extra_attributes ,
                                orders.from_cashier     as from_cashier
                               "
                ),
                DB::raw(
                    " ( IFNULL( 
                                    (SELECT count(order_vendors.id) FROM order_vendors
                                        WHERE orders.id = order_vendors.order_id
                                    
                                        GROUP BY order_vendors.order_id
                                    )
                            , 0)) as vendors_count"
                ),
                DB::raw("
                                ( IFNULL( 
                                    (SELECT SUM(order_products.qty) FROM order_products
                                        WHERE orders.id = order_products.order_id
                                        and order_products.is_refund = 0
                                        GROUP BY order_products.order_id
                                    )
                            , 0) +  IFNULL(
                                (
                                    SELECT SUM(order_variant_products.qty) FROM order_variant_products
                                    WHERE orders.id = order_variant_products.order_id
                                    and order_variant_products.is_refund = 0
                                    GROUP BY order_variant_products.order_id
                                )
                                , 0)
                                ) as qty
                            ")
            )
            ->join("users", "users.id", "=", "orders.user_id")
            ->join("users as cashiers", "cashiers.id", "=", "orders.cashier_id")
            ->join("order_vendors", "order_vendors.order_id", "=", "orders.id")
            ->leftJoin("branches", "orders.branch_id", "=", "branches.id")
            ->leftJoin("branch_translations", function ($join) use ($request) {
                $join->on("branches.id", "=", "branch_translations.branch_id")
                    ->where("branch_translations.locale", locale());
            })
            ->join("order_statuses", function ($join) {
                $join->on("order_statuses.id", "=", "orders.order_status_id")
                    ->where("code", 3);
            })
            ->join("transactions", function ($join) {
                $join->on("transactions.transaction_id", "orders.id")
                    ->where("transactions.transaction_type", Order::class);
            })
            ->when($request->input('search.value'), function ($query) use ($request) {
                $query->where('orders.id', 'like', '%'.$request->input('search.value').'%');
            })
            ->when($request->input('req.method_transaction'), function ($query) use ($request) {
                $query->where("transactions.method", $request->input('req.method_transaction'));
            });
        $order = $this->filterDateTableDate($order, "orders", $request);
        $order = $this->filterDateTableVendor($order, "order_vendors.vendor_id", $request, $vendors);
        $order = $this->searchBranchIdDatatable($order, $request);
        $order = $this->searchFromCashierDatatable($order, $request);
        return $order;
    }


    public function orderRefund($request, $vendors)
    {
        $order = Order::whereHas('orderStatus', function ($query) {
            $query->refundOrder();
        })
            ->with(["user", "cashier", "transactions", "branch"])
            ->withCount([
                "vendors", "productRefunds as products_refund_qty" => function ($query) {
                    return $query->select(DB::raw("sum(order_refund_items.qty)"));
                },
                "variationsRefunds as variations_refund_qty" => function ($query) {
                    return $query->select(DB::raw("sum(order_refund_items.qty)"));
                }

            ])
            ->when($request->input('search.value'), function ($query) use ($request) {
                $query->where('orders.id', 'like', '%'.$request->input('search.value').'%');
            })
            ->where(function ($query) use ($vendors) {
                if ($vendors != 'all') {

                    $query->whereHas("vendors", function ($query) use ($vendors) {
                        $query->whereIn("vendors.id", (array) $vendors);
                    });
                }
            })
            ->when($request->input('req.vendor_id'), function ($query) use ($request) {
                $query->whereHas("vendors", function ($query) use ($request) {
                    $query->where("vendors.id", $request->input('req.vendor_id'));
                });
            })
            ->when($request->input('req.cashier_id'), function ($query) use ($request) {
                $query->where("cashier_id", $request->input('req.cashier_id'));
            });

        $order = $this->filterDateTableDate($order, "orders", $request);


        return $order;
    }


    public function productStock($request, $vendors)
    {
        $type = "";
        if (isset($request['req']['type']) && $request['req']['type'] != '') {
            $type = $request['req']['type'];
        }

        $products = DB::table("products")
            ->select(
                \DB::raw(
                    "product_translations.title as title  ,
                                     products.id                as id     ,
                                     products.price             as price  ,
                                     products.created_at        as created_at,
                                     vendor_translations.title  as vendor_title ,
                                     branch_translations.title as branch 
                            "
                ),
                DB::raw(
                    " ( IFNULL( 
                                        (SELECT sum(product_variants.qty) FROM product_variants
                                            WHERE products.id = product_variants.product_id
                                            GROUP BY product_variants.product_id
                                        )
                                , products.qty)) as qty"
                ),
                DB::raw(
                    " ( IF( 
                                    (SELECT count(product_variants.id) FROM product_variants
                                        WHERE products.id = product_variants.product_id
                                        GROUP BY product_variants.product_id
                                    ) > 0
                                    , 'variant', 'product')) as type"
                ),
                DB::raw("
                                        ( IFNULL( 
                                            (SELECT SUM(order_products.qty) FROM order_products
                                                WHERE products.id = order_products.product_id
                                                and order_products.is_refund = 0
                                                GROUP BY order_products.product_id
                                            )
                                    , 0) +  IFNULL(
                                        (
                                            SELECT SUM(order_variant_products.qty) FROM order_variant_products
                                            join product_variants
                                            on product_variants.id   = order_variant_products.product_variant_id
                                            WHERE products.id = product_variants.product_id
                                            and order_variant_products.is_refund = 0
                                            GROUP BY product_variants.product_id
                                        )
                                        , 0)
                                        ) as total
                                ")
            )
            ->join("vendors", "vendors.id", "=", "products.vendor_id")
            ->join("vendor_translations", function ($join) use ($request) {
                $join->on("vendors.id", "=", "vendor_translations.vendor_id")
                    ->where("vendor_translations.locale", locale());
            })
            ->join("branches", "products.branch_id", "=", "branches.id")
            ->join("product_translations", function ($join) use ($request) {
                $join->on("products.id", "=", "product_translations.product_id")
                    ->where("product_translations.locale", locale());
            })
            ->join("branch_translations", function ($join) use ($request) {
                $join->on("branches.id", "=", "branch_translations.branch_id")
                    ->where("branch_translations.locale", locale());
            })
            ->when($type == "variant", function ($query) {
                $query->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('product_variants')
                        ->whereColumn('products.id', 'product_variants.product_id');
                });
            })
            ->when($type == "product", function ($query) {
                $query->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('product_variants')
                        ->whereColumn('products.id', 'product_variants.product_id');
                });
            });

        $products = $this->filterDateTableDate($products, "products", $request);
        $products = $this->searchProductNameDaTable($products, $request);
        $products = $this->filterDateTableVendor($products, "products.vendor_id", $request, $vendors);
        return $products;
    }

    public function vendors($request, $vendors)
    {
        $sallerVendorsQuery = $vendors != 'all' ? "and vendors.id IN ('".implode(',',$vendors)."')" : "";

        $withTotalCount = " ( IFNULL( 
                    (SELECT sum(order_vendors.subtotal) FROM order_vendors
                        INNER JOIN orders ON order_vendors.order_id = orders.id
                        WHERE 
                         vendors.id = order_vendors.vendor_id
                         ".$sallerVendorsQuery."
                         #OTHER_QUERY
                        GROUP BY order_vendors.vendor_id
                    )
            , 0)) as total";


        $withRefundCount = "
                ( IFNULL( 
                    (SELECT SUM(order_refund_items.qty) FROM order_refund_items
                        INNER JOIN order_products ON order_products.id = order_refund_items.item_id
                        and order_refund_items.item_type = '".addslashes(OrderProduct::class)."'
                        INNER JOIN products ON order_products.product_id = products.id
                        INNER JOIN orders ON order_products.order_id = orders.id
                        WHERE 
                            vendors.id = products.vendor_id
                            ".$sallerVendorsQuery."

                            #OTHER_QUERY

                        GROUP BY products.vendor_id
                    )
            , 0) +  IFNULL(
                (
                    (SELECT SUM(order_refund_items.qty) FROM order_refund_items
                        INNER JOIN order_variant_products ON order_variant_products.id = order_refund_items.item_id
                        and order_refund_items.item_type = '".addslashes(OrderVariantProduct::class)."'
                        INNER JOIN product_variants ON product_variants.id = order_variant_products.product_variant_id
                        INNER JOIN products ON product_variants.product_id = products.id
                        INNER JOIN orders ON order_variant_products.order_id = orders.id
                        WHERE 
                             vendors.id = products.vendor_id
                             ".$sallerVendorsQuery."
                            
                             #OTHER_QUERY

                        GROUP BY products.vendor_id
                    )
                )
                , 0)
                ) as total_refund
        ";
        $defultFilterTotal = "
                            and MONTH(orders.created_at) = MONTH(CURRENT_DATE()) and
                            YEAR(orders.created_at) = YEAR(CURRENT_DATE())";

        if ($request->input('req.from') || $request->input('req.to')) {
            $defultFilterTotal = "";
            if ($request->input('req.from')) {
                $defultFilterTotal .= " and DATE(orders.created_at) >= '".$request->input('req.from')."'";
            }
            if ($request->input('req.to')) {
                $defultFilterTotal .= " and DATE(orders.created_at) <= '".$request->input('req.to')."'";
            }
        }
        $withTotalCount = str_replace("#OTHER_QUERY", $defultFilterTotal, $withTotalCount);
        $withRefundCount = str_replace("#OTHER_QUERY", $defultFilterTotal, $withRefundCount);

        $vendors = DB::table("vendors")
            ->select(
                DB::raw("
                                vendors.id                 as id ,
                                vendor_translations.title  as title  , 
                                vendors.created_at         as created_at
                             "),
                DB::raw($withTotalCount),
                DB::raw($withRefundCount),
                     )
            ->join("vendor_translations", function ($join) use ($request) {
                $join->on("vendors.id", "=", "vendor_translations.vendor_id")
                    ->where("vendor_translations.locale", locale());
            })->where(function ($query) use ($vendors) {
                if ($vendors != 'all') {
                    $query->whereIn('vendors.id', (array)$vendors);
                }
            });

        $vendors = $this->searchVendorNameDaTable($vendors, $request);
        return $vendors;
    }
}