<?php

namespace Modules\Catalog\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Entities\Vendor;
use Modules\Core\Traits\CoreTrait;

class VendorRepository
{
    use CoreTrait;

    protected $vendor;
    protected $product;
    protected $prodCategory;

    public function __construct(Vendor $vendor, Product $product, Category $prodCategory)
    {
        $this->vendor = $vendor;
        $this->product = $product;
        $this->prodCategory = $prodCategory;
    }

    public function countVendors()
    {
        $vendors = $this->vendor->count();
        return $vendors;
    }

    public function countSubscriptionsVendors()
    {
        $query = $this->vendor->query();

        $query->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        return $query->count();
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $vendors = $this->vendor->orderBy($order, $sort)->get();
        return $vendors;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        return $this->vendor->active()->orderBy($order, $sort)->get();
    }

    public function getAllActiveProdCategories($order = 'id', $sort = 'desc')
    {
        return $this->prodCategory->active()->orderBy($order, $sort)->get();
    }

    public function findById($id)
    {
        $vendor = $this->vendor->withDeleted()->find($id);
        return $vendor;
    }

    public function getActiveVendorsWithLimitProducts($minQty)
    {
        $query = $this->vendor->active();

        $query = $query->when(config('setting.other.enable_subscriptions') == 1, function ($q) {
            return $q->whereHas('subbscription', function ($query) {
                $query->active()->unexpired()->started();
            });
        });

        if (config('setting.other.is_multi_vendors') == 0) {
            $query = $query->where('id', config('setting.default_vendor'));
        }

        $query = $query->with(['products' => function ($q) use ($minQty) {
            $q->active();
            $q->where(function ($q) use ($minQty) {
                $q->where('qty', '<=', $minQty);
                $q->orWhereHas('variants', function ($q) use ($minQty) {
                    $q->active();
                    $q->where('qty', '<=', $minQty);
                });
            });
        }]);

        $query = $query->whereHas('products', function ($q) use ($minQty) {
            $q->active();
            $q->where(function ($q) use ($minQty) {
                $q->where('qty', '<=', $minQty);
                $q->orWhereHas('variants', function ($q) use ($minQty) {
                    $q->active();
                    $q->where('qty', '<=', $minQty);
                });
            });
        });
        return $query->get();
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $vEmails = $this->removeEmptyValuesFromArray($request->emails);
            $data = [
                'status' => $request->status ? 1 : 0,
                "title" => $request->title,
                "description" => $request->description,
            ];

            if (!is_null($request->image)) {
                $imgName = $this->uploadImage(public_path(config('core.config.vendor_img_path')), $request->image);
                $data['image'] = config('core.config.vendor_img_path') . '/' . $imgName;
            } else {
                $data['image'] = url(config('setting.images.logo'));
            }

            $vendor = $this->vendor->create($data);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();

        $vendor = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelete($vendor) : null;

        try {
            $vEmails = $this->removeEmptyValuesFromArray($request->emails);
            $data = [
                'status' => $request->status ? 1 : 0,
                "title" => $request->title,
                "description" => $request->description,
            ];

            if ($request->image) {
                File::delete($vendor->image); ### Delete old image
                $imgName = $this->uploadImage(config('core.config.vendor_img_path'), $request->image);
                $data['image'] = config('core.config.vendor_img_path') . '/' . $imgName;
            } else {
                $data['image'] = $vendor->image;
            }

            $vendor->update($data);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function syncRelationModel($model, $relation, $columnName = 'id', $arrayValues = null)
    {
        $oldIds = $model->$relation->pluck($columnName)->toArray();
        $data['deleted'] = array_values(array_diff($oldIds, $arrayValues));
        $data['updated'] = array_values(array_intersect($oldIds, $arrayValues));
        return $data;
    }

    public function updateInfo($request, $id)
    {
        DB::beginTransaction();

        $vendor = $this->findById($id);

        try {

            $vendor->update([
                'vendor_status_id' => $request->vendor_status_id,
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateVendorStatus($request, $id)
    {
        DB::beginTransaction();
        $vendor = $this->findById($id);
        $status = filter_var($request->vendor_status_id, FILTER_VALIDATE_BOOLEAN);
        try {
            $vendor->update([
                'vendor_status_id' => $status == true ? 4 : null,
            ]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function sorting($request)
    {
        DB::beginTransaction();

        try {

            foreach ($request['vendors'] as $key => $value) {

                $key++;

                $this->vendor->find($value)->update([
                    'sorting' => $key,
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelete($model)
    {
        $model->restore();
        return true;
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            if ($model) {
                File::delete($model->image);
            }
            ### Delete old image

            if ($model->trashed()):
                $model->forceDelete();
            else:
                $model->delete();
            endif;

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteSelected($request)
    {
        DB::beginTransaction();

        try {

            foreach ($request['ids'] as $id) {
                $model = $this->delete($id);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getAllPaginatedProducts($request, $order = 'id', $sort = 'desc', $count = 50)
    {
        $query = $this->product->orderBy($order, $sort)->active();

        if (isset($request->category) && !empty($request->category)) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('product_categories.category_id', $request->category);
            });
        }

        return $query->paginate($count);
    }

    public function assignVendorProducts($vendor, $request)
    {
        DB::beginTransaction();

        try {
            if (isset($request->ids) && !empty($request->ids)) {
                foreach ($request->ids as $k => $id) {
                    $pivotArray = ['price' => $request->price[$id], 'qty' => $request->qty[$id]];
                    if (isset($request->status[$id])) {
                        $pivotArray['status'] = isset($request->status[$id]) || $request->status[$id] == 'on' ? 1 : 0;
                    } else {
                        $pivotArray['status'] = 0;
                    }
                    $products_array[$id] = $pivotArray;
                }
                // sync without delete old items
                $vendor->products()->sync($products_array, false);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function QueryTable($request)
    {
        $query = $this->vendor->with([]);

        $query->where(function ($query) use ($request) {

            $query
                ->where('id', 'like', '%' . $request->input('search.value') . '%')
                ->orWhere(function ($query) use ($request) {
                    $query->where('description', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhere('title', 'like', '%' . $request->input('search.value') . '%');
                    $query->orWhere('slug', 'like', '%' . $request->input('search.value') . '%');
                });
        });

        return $this->filterDataTable($query, $request);
    }

    public function filterDataTable($query, $request)
    {
        // Search Pages by Created Dates
        if (isset($request['req']['from']) && $request['req']['from'] != '') {
            $query->whereDate('created_at', '>=', $request['req']['from']);
        }

        if (isset($request['req']['to']) && $request['req']['to'] != '') {
            $query->whereDate('created_at', '<=', $request['req']['to']);
        }

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'only') {
            $query->onlyDeleted();
        }

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'with') {
            $query->withDeleted();
        }

        if (isset($request['req']['status']) && $request['req']['status'] == '1') {
            $query->active();
        }

        if (isset($request['req']['status']) && $request['req']['status'] == '0') {
            $query->unactive();
        }

        if (isset($request['req']['sections']) && $request['req']['sections'] != '') {

            $query->where('section_id', $request['req']['sections']);

            /* $query->whereHas('sections', function ($query) use ($request) {
        $query->where('section_id', $request['req']['sections']);
        }); */
        }

        return $query;
    }
}
