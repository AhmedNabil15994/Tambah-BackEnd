<?php

namespace Modules\POS\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Modules\Core\Traits\DataTable;
use Illuminate\Routing\Controller;
use Modules\POS\Repositories\Dashboard\PosOrderRepository as Repo;
use Modules\POS\Transformers\Dashboard\PosOrderResource;

class PosOrderController extends Controller
{
    protected $repo;

    function __construct(Repo $repo)
    {
        $this->repo = $repo;
    }

    public function getPosOrders()
    {
        return view('pos::dashboard.pos_orders.index');
    }

    public function posOrdersDatatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->repo->posOrdersDatatable($request), 'orders');
        $datatable['data'] = PosOrderResource::collection($datatable['data']);
        return Response()->json($datatable);
    }
}
