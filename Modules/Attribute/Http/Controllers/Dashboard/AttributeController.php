<?php

namespace Modules\Attribute\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Attribute\Repositories\Dashboard\AttributeRepository as Repo;
use Modules\Attribute\Transformers\Dashboard\AttributeResource as ModelResource;
use Modules\Attribute\Http\Requests\Dashboard\AttributeRequest as ModelRequest;
use Modules\Attribute\Transformers\Dashboard\ChildAttributes;

class AttributeController extends Controller
{
    protected $repo;

    function __construct(Repo $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return view('attribute::dashboard.attributes.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->repo->QueryTable($request));
        $datatable['data'] = ModelResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function create()
    {
        $allAttributes = $this->repo->getAllForForm(['options']);
        $model = $this->repo->getModel();
        return view('attribute::dashboard.attributes.create',compact('model','allAttributes'));
    }

    public function store(ModelRequest $request)
    {
        try {
            $create = $this->repo->create($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
            }

            return Response()->json([true, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function show($id)
    {
        return view('attribute::dashboard.attributes.show');
    }

    public function edit($id)
    {

        $model = $this->repo->findById($id, ["options"]);
        $allAttributes = $this->repo->getAllForForm(['options']);
        $childAttributes = optional($model->childAttributes())->get();

        return view('attribute::dashboard.attributes.edit', compact('model','allAttributes','childAttributes'));
    }

    public function update(ModelRequest $request, $id)
    {
        try {
            $update = $this->repo->update($request, $id);

            if ($update) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([true, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function destroy($id)
    {
        try {
            $delete = $this->repo->delete($id);

            if ($delete) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([true, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        try {
            $deleteSelected = $this->repo->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([true, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
