<?php

namespace Modules\User\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\User\Http\Requests\Dashboard\CashierRequest;
use Modules\User\Transformers\Dashboard\CashierResource;
use Modules\User\Repositories\Dashboard\CashierRepository as Cashier;
use Modules\Authorization\Repositories\Dashboard\RoleRepository as Role;

class CashierController extends Controller
{
    protected $role;
    protected $cashier;

    function __construct(Cashier $cashier, Role $role)
    {
        $this->role = $role;
        $this->cashier = $cashier;
    }

    public function index()
    {
        return view('user::dashboard.cashiers.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->cashier->QueryTable($request));

        $datatable['data'] = CashierResource::collection($datatable['data']);

        return Response()->json($datatable);
    }

    public function create()
    {
        $roles = $this->role->getAllcashiersRoles('id', 'asc');
        return view('user::dashboard.cashiers.create', compact('roles'));
    }

    public function store(CashierRequest $request)
    {
        try {
            $create = $this->cashier->create($request);

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
        abort(404);
        return view('user::dashboard.cashiers.show');
    }

    public function edit($id)
    {
        $user = $this->cashier->findById($id);
        $roles = $this->role->getAllcashiersRoles('id', 'asc');

        return view('user::dashboard.cashiers.edit', compact('user', 'roles'));
    }

    public function update(CashierRequest $request, $id)
    {
        try {
            $update = $this->cashier->update($request, $id);

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
            $delete = $this->cashier->delete($id);

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
            $deleteSelected = $this->cashier->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
