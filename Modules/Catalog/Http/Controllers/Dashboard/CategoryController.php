<?php

namespace Modules\Catalog\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Catalog\Http\Requests\Dashboard\ImportCategoryRequest;
use Modules\Catalog\Imports\CategoryImport;
use Modules\Core\Traits\DataTable;
use Modules\Catalog\Http\Requests\Dashboard\CategoryRequest;
use Modules\Catalog\Transformers\Dashboard\CategoryResource;
use Modules\Catalog\Http\Requests\Dashboard\updatePhotoRequest;
use Modules\Catalog\Repositories\Dashboard\CategoryRepository as Category;
use Modules\Core\Traits\Dashboard\DatatableExportTrait;

class CategoryController extends Controller
{
    use DatatableExportTrait;

    protected $category;

    function __construct(Category $category)
    {
        $this->category = $category;
        $this->setRepository(Category::class);
        $this->setResource(new CategoryResource([]));
    }

    public function index()
    {
        return view('catalog::dashboard.categories.index');
    }

    public function create()
    {
        return view('catalog::dashboard.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        try {
            $create = $this->category->create($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function updatePhoto(updatePhotoRequest $request)
    {
        try {
            $imagePath = $this->category->updatePhoto($request);

            if ($imagePath) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success'), 'imagePath' => $imagePath]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            throw $e;
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function Import(ImportCategoryRequest $request)
    {
        try {

            DB::beginTransaction();
            Excel::import(new CategoryImport($request), $request->file('excel_file'));
            DB::commit();
            return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function show($id)
    {
        return view('catalog::dashboard.categories.show');
    }

    public function edit($id)
    {
        $category = $this->category->findById($id);

        return view('catalog::dashboard.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, $id)
    {
        try {
            $update = $this->category->update($request, $id);

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
            $delete = false;
            if ($id != 1)
                $delete = $this->category->delete($id);

            if ($delete)
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        try {
            $deleteSelected = $this->category->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
