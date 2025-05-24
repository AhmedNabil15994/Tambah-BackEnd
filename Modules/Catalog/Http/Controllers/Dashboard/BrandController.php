<?php

namespace Modules\Catalog\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Catalog\Http\Requests\Dashboard\BrandRequest;
use Modules\Catalog\Transformers\Dashboard\BrandResource;
use Modules\Catalog\Repositories\Dashboard\BrandRepository as BrandRepo;

class BrandController extends Controller
{
    protected $brand;

    function __construct(BrandRepo $brand)
    {
        $this->brand = $brand;
    }

    public function index()
    {
        return view('catalog::dashboard.brands.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->brand->QueryTable($request));
        $datatable['data'] = BrandResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function create()
    {
        return view('catalog::dashboard.brands.create');
    }

    public function store(BrandRequest $request)
    {
        try {
            $create = $this->brand->create($request);

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
        return view('catalog::dashboard.brands.show');
    }

    public function edit($id)
    {
        $brand = $this->brand->findById($id);
        if (!$brand)
            abort(404);
        return view('catalog::dashboard.brands.edit', compact('brand'));
    }

    public function clone($id)
    {
        $brand = $this->brand->findById($id);
        if (!$brand)
            abort(404);
        return view('catalog::dashboard.brands.clone', compact('brand'));
    }

    public function update(BrandRequest $request, $id)
    {
        try {
            $update = $this->brand->update($request, $id);

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
            $delete = $this->brand->delete($id);

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
            $deleteSelected = $this->brand->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
