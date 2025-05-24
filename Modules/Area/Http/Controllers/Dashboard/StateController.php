<?php

namespace Modules\Area\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Area\Http\Requests\Dashboard\StateRequest;
use Modules\Area\Transformers\Dashboard\StateResource;
use Modules\Area\Repositories\Dashboard\StateRepository as State;
use Modules\Area\Transformers\FrontEnd\AreaSelectorResource;
use Modules\Area\Entities\Country;

class StateController extends Controller
{

    function __construct(State $state)
    {
        $this->state = $state;
    }

    public function index()
    {
        return view('area::dashboard.states.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->state->QueryTable($request));

        $datatable['data'] = StateResource::collection($datatable['data']);

        return Response()->json($datatable);
    }

    public function create()
    {
        return view('area::dashboard.states.create');
    }

    public function store(StateRequest $request)
    {
        try {
            $create = $this->state->create($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function show($id)
    {
        return view('area::dashboard.states.show');
    }

    public function edit($id)
    {
        $state = $this->state->findById($id);

        return view('area::dashboard.states.edit', compact('state'));
    }

    public function update(StateRequest $request, $id)
    {
        try {
            $update = $this->state->update($request, $id);

            if ($update) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function destroy($id)
    {
        try {
            $delete = $this->state->delete($id);

            if ($delete) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        try {
            $deleteSelected = $this->state->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function getChildAreaByParent(Request $request)
    {
        $items = AreaSelectorResource::collection($this->state->getChildAreaByParent($request));
        $country = null;

        if ($request->type == 'city') {
            $country = Country::active()->findOrFail($request->parent_id)->iso2;
        }

        return response()->json(['success' => true, 'data' => $items, 'country' => $country]);
    }
}
