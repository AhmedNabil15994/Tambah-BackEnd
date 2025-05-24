<?php

namespace Modules\Catalog\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Catalog\Http\Requests\Dashboard\AgeRequest;
use Modules\Catalog\Transformers\Dashboard\AgeResource;
use Modules\Catalog\Repositories\Dashboard\AgeRepository as AgeRepo;

class AgeController extends Controller
{
    protected $age;

    function __construct(AgeRepo $age)
    {
        $this->age = $age;
    }

    public function index()
    {
        return view('catalog::dashboard.ages.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->age->QueryTable($request));
        $datatable['data'] = AgeResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function create()
    {
        return view('catalog::dashboard.ages.create');
    }

    public function store(AgeRequest $request)
    {
        try {
            $create = $this->age->create($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function show($id)
    {
        return view('catalog::dashboard.ages.show');
    }

    public function edit($id)
    {
        $age = $this->age->findById($id);
        if (!$age)
            abort(404);
        return view('catalog::dashboard.ages.edit', compact('age'));
    }

    public function clone($id)
    {
        $age = $this->age->findById($id);
        if (!$age)
            abort(404);
        return view('catalog::dashboard.ages.clone', compact('age'));
    }

    public function update(AgeRequest $request, $id)
    {
        try {
            $update = $this->age->update($request, $id);

            if ($update) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function destroy($id)
    {
        try {
            $delete = $this->age->delete($id);

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
            $deleteSelected = $this->age->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
