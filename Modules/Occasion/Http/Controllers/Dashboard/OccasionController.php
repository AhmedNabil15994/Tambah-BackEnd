<?php

namespace Modules\Occasion\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Occasion\Transformers\Dashboard\OccasionResource;
use Modules\Occasion\Repositories\Dashboard\OccasionRepository as Occasion;

class OccasionController extends Controller
{
    protected $occasion;

    function __construct(Occasion $occasion)
    {
        $this->occasion = $occasion;
    }

    public function index()
    {
        return view('occasions::dashboard.occasion.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->occasion->QueryTable($request));

        $datatable['data'] = OccasionResource::collection($datatable['data']);

        return Response()->json($datatable);
    }

    public function destroy($id)
    {
        try {
            $delete = $this->occasion->delete($id);

            if ($delete) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        try {
            $deleteSelected = $this->occasion->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
