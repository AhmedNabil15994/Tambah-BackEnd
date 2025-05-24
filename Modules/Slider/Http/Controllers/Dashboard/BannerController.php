<?php

namespace Modules\Slider\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Traits\DataTable;
use Modules\Slider\Http\Requests\Dashboard\BannerRequest;
use Modules\Slider\Transformers\Dashboard\BannerResource;
use Modules\Slider\Repositories\Dashboard\BannerRepository as Banner;

class BannerController extends Controller
{
    protected $banner;

    function __construct(Banner $banner)
    {
        $this->banner = $banner;
    }

    public function index()
    {
        return view('slider::dashboard.banner.index');
    }

    public function datatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->banner->QueryTable($request));
        $datatable['data'] = BannerResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function create()
    {
        return view('slider::dashboard.banner.create');
    }

    public function store(BannerRequest $request)
    {
        try {
            $create = $this->banner->create($request);

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
        return view('slider::dashboard.banner.show');
    }

    public function edit($id)
    {
        $banner = $this->banner->findById($id);

        return view('slider::dashboard.banner.edit', compact('banner'));
    }

    public function update(BannerRequest $request, $id)
    {
        try {
            $update = $this->banner->update($request, $id);

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
            $delete = $this->banner->delete($id);

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
            $deleteSelected = $this->banner->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
