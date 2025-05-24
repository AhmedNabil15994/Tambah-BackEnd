<?php

namespace Modules\Wrapping\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Wrapping\Http\Requests\Dashboard\AddonsRequest;
use Modules\Wrapping\Transformers\Dashboard\AddonsResource;
use Modules\Wrapping\Repositories\Dashboard\AddonsRepository as WrappingAddons;

class AddonsController extends Controller
{
    protected $addons;

    function __construct(WrappingAddons $addons)
    {
        $this->addons = $addons;
    }

    public function index()
    {
        return view('wrapping::dashboard.addons.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->addons->QueryTable($request));
        $datatable['data'] = AddonsResource::collection($datatable['data']);

        return Response()->json($datatable);
    }

    public function create()
    {
        return view('wrapping::dashboard.addons.create');
    }

    public function store(AddonsRequest $request)
    {
        try {
            $create = $this->addons->create($request);

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
        return view('wrapping::dashboard.addons.show');
    }

    public function edit($id)
    {
        $addons = $this->addons->findById($id);
        return view('wrapping::dashboard.addons.edit', compact('addons'));
    }

    public function clone($id)
    {
        $addons = $this->addons->findById($id);

        return view('wrapping::dashboard.addons.clone', compact('addons'));
    }

    public function update(AddonsRequest $request, $id)
    {
        try {
            $update = $this->addons->update($request, $id);

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
            $delete = $this->addons->delete($id);

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
            $deleteSelected = $this->addons->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
