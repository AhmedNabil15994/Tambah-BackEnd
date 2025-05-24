<?php

namespace Modules\Wrapping\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Wrapping\Http\Requests\Dashboard\GiftRequest;
use Modules\Wrapping\Transformers\Dashboard\GiftResource;
use Modules\Wrapping\Repositories\Dashboard\GiftRepository as Gift;

class GiftController extends Controller
{
    protected $gift;

    function __construct(Gift $gift)
    {
        $this->gift = $gift;
    }

    public function index()
    {
        return view('wrapping::dashboard.gifts.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->gift->QueryTable($request));

        $datatable['data'] = GiftResource::collection($datatable['data']);

        return Response()->json($datatable);
    }

    public function create()
    {
        return view('wrapping::dashboard.gifts.create');
    }

    public function store(GiftRequest $request)
    {
        try {
            $create = $this->gift->create($request);

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
        return view('wrapping::dashboard.gifts.show');
    }

    public function edit($id)
    {
        $gift = $this->gift->findById($id);
        return view('wrapping::dashboard.gifts.edit', compact('gift'));
    }

    public function clone($id)
    {
        $gift = $this->gift->findById($id);

        return view('wrapping::dashboard.gifts.clone', compact('gift'));
    }

    public function update(GiftRequest $request, $id)
    {
        try {
            $update = $this->gift->update($request, $id);

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
            $delete = $this->gift->delete($id);

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
            $deleteSelected = $this->gift->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
