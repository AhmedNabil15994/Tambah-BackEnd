<?php

namespace Modules\Catalog\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Apps\Http\Requests\Dashboard\GetExcelHeaderRowRequest;
use Modules\Apps\Imports\GetFirstRowImport;
use Modules\Catalog\Http\Requests\Dashboard\AddOnsRequest;
use Modules\Catalog\Http\Requests\Dashboard\ImportProductPhotoRequest;
use Modules\Catalog\Http\Requests\Dashboard\ImportProductRequest;
use Modules\Catalog\Http\Requests\Dashboard\ProductRequest;
use Modules\Catalog\Http\Requests\Dashboard\updatePhotoRequest;
use Modules\Catalog\Repositories\Dashboard\ProductRepository as Product;
use Modules\Catalog\Transformers\Dashboard\ExportProductResource;
use Modules\Catalog\Transformers\Dashboard\ProductResource;
use Modules\Core\Traits\Dashboard\DatatableExportTrait;
use Modules\Core\Traits\DataTable;

class ProductController extends Controller
{
    use DatatableExportTrait;
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->setRepository(Product::class);
        $this->setResource(new ProductResource([]));
        $this->setExportResource(new ExportProductResource([]));
    }

    public function index()
    {
        return view('catalog::dashboard.products.index');
    }

    public function reviewProducts()
    {
        return view('catalog::dashboard.products.review-products.index');
    }

    public function reviewProductsDatatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->product->reviewProductsQueryTable($request));
        $datatable['data'] = ProductResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function create()
    {
        return view('catalog::dashboard.products.create');
    }

    public function store(ProductRequest $request)
    {
        try {
            $create = $this->product->create($request);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function Import(ImportProductRequest $request)
    {
        try {

            $this->product->import($request);

            return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function importPhoto(ImportProductPhotoRequest $request)
    {
        try {

            $this->product->importPhotos($request);
            return Response()->json([true, __('apps::dashboard.general.message_create_success')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function show($id)
    {
        return abort(404);

        /*$product = $this->product->getProductDetailsById($id);
    if (!$product)
    return abort(404);

    return view('catalog::dashboard.products.show', compact('product'));*/
    }

    public function edit($id)
    {
        $product = $this->product->findById($id);
        if (!$product) {
            return abort(404);
        }

        $product->load(["variantValues", "variants.productValues.optionValue.option", "categories"]);
        $currentVaration = $product->variantValues->sortBy("option_value_id")->groupBy("product_variant_id")->pluck("*.option_value_id")->toArray();

        return view('catalog::dashboard.products.edit', compact('product', "currentVaration"));
    }

    public function addOns($id)
    {
        if (config('setting.products.toggle_addons') != 1) {
            return abort(404);
        }

        $product = $this->product->findById($id);
        if (!$product) {
            return abort(404);
        }

        return view('catalog::dashboard.products.add_ons', compact('product'));
    }

    public function updatePhoto(updatePhotoRequest $request)
    {
        try {
            $imagePath = $this->product->updatePhoto($request);

            if ($imagePath) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success'), 'imagePath' => $imagePath]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            throw $e;
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function storeAddOns($id, AddOnsRequest $request)
    {
        try {
            $create = $this->product->createAddOns($request, $id);

            if ($create) {
                return Response()->json([true, __('apps::dashboard.general.message_create_success'), 'data' => $create]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            throw $e;
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    function clone ($id) {
        $product = $this->product->findById($id);
        if (!$product) {
            return abort(404);
        }

        return view('catalog::dashboard.products.clone', compact('product'));
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            $update = $this->product->update($request, $id);

            if ($update) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function approveProduct(Request $request, $id)
    {
        try {
            $update = $this->product->approveProduct($request, $id);

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
            $delete = $this->product->delete($id);

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
            $deleteSelected = $this->product->deleteSelected($request);

            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deleteAddOns(Request $request)
    {
        try {
            $addOnId = $request->id;
            $addOns = $this->product->findAddOnsById($addOnId);

            if ($addOns) {
                $delete = $this->product->deleteAddOns($addOnId);
                if ($delete) {
                    return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
                } else {
                    return Response()->json([false, __('apps::dashboard.general.message_error')]);
                }

            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deleteAddOnsOption(Request $request)
    {
        try {
            $addOnOptionId = $request->id;
            $addOnsOption = $this->product->findAddOnsOptionById($addOnOptionId);

            if ($addOnsOption) {
                $delete = $this->product->deleteAddOnsOption($addOnOptionId);
                if ($delete) {
                    return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
                } else {
                    return Response()->json([false, __('apps::dashboard.general.message_error')]);
                }

            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deleteProductImage(Request $request)
    {
        try {
            $id = $request->id;
            $prdImg = $this->product->findProductImgById($id);

            if ($prdImg) {
                $delete = $this->product->deleteProductImg($id);
                if ($delete) {
                    return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
                } else {
                    return Response()->json([false, __('apps::dashboard.general.message_error')]);
                }

            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\Exception $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function getExcelHeaderRow(GetExcelHeaderRowRequest $request)
    {
        $rows = Excel::toArray(new GetFirstRowImport, $request->file('excel_file'));
        return response()->json([true, 'ignore_success' => true, 'header_row' => $rows[0][0]]);
    }

    public function switcher($id, $action)
    {
        try {
            $switch = $this->product->switcher($id, $action);

            if ($switch) {
                return Response()->json();
            }

            return Response()->json([false, __('apps::dashboard.messages.failed')], 400);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }
}
