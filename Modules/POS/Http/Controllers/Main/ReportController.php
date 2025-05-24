<?php

namespace Modules\POS\Http\Controllers\Main;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\POS\Transformers\Report\OrderRefundItemResource;
use Modules\POS\Transformers\Report\OrderRefundResource;
use Modules\POS\Transformers\Report\ProductsSaleResource;


abstract class ReportController extends Controller
{
    protected $repo;
    protected $path;

    public function porodctsSale(Request $request)
    {
        return view($this->path . '.product-sales');
    }

    public function ordersSale(Request $request)
    {        
        return view($this->path . '.order-sales');
    }

    public function refundSale(Request $request)
    {      
        return view($this->path . '.refund');
    }

    public function refundOrders(Request $request)
    {        
        return view($this->path . '.order-refund');
    }

    public function productStock(Request $request)
    {       
        return view($this->path . '.product-stock');
    }

    public function vendorTotal(Request $request)
    {    
        return view($this->path . '.vendors');
    }

    public function vendorTotalDataTable(Request $request)
    {      
        $datatable = DataTable::drawTable($request, $this->repo->vendors($request, self::checkVendorPermissionAndGetVendors()));

        $datatable['data'] = $datatable['data'];

        return Response()->json($datatable);
    }

    public function productStockDataTable(Request $request)
    {     
        $datatable = DataTable::drawTable($request, $this->repo->productStock($request, self::checkVendorPermissionAndGetVendors()));

        $datatable['data'] = $datatable['data'];

        return Response()->json($datatable);
    }

    public function porodctsSaleDataTable(Request $request)
    {
        
        $datatable = DataTable::drawTable($request, $this->repo->productSales($request , self::checkVendorPermissionAndGetVendors()));

        $datatable['data'] = ProductsSaleResource::collection($datatable['data']);

        return Response()->json($datatable);
    }

    public function ordersSaleDataTable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->repo->orderSalesSql($request, self::checkVendorPermissionAndGetVendors()));
        $datatable['data'] = ($datatable['data']);

        return Response()->json($datatable);
    }

    public function refundSaleDataTable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->repo->refundSales($request, self::checkVendorPermissionAndGetVendors()));
        $datatable['data'] = OrderRefundItemResource::collection( $datatable['data'] );

        return Response()->json($datatable);
    }

    public function ordersRefundDataTable(Request $request)
    { 
        $datatable = DataTable::drawTable($request, $this->repo->orderRefund($request, self::checkVendorPermissionAndGetVendors()));
        $datatable['data'] = OrderRefundResource::collection($datatable['data']);

        return Response()->json($datatable);
    }
    
    static function checkVendorPermissionAndGetVendors(){

        $user = auth()->user();

        if($user->can('dashboard_access')){
            return 'all';
        }

        if($user->can('seller_access')){
            return $user->vendors->pluck('id')->toArray();
        }

        return [];
    }
    
}