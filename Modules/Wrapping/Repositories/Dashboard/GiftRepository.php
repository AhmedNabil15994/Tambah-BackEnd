<?php

namespace Modules\Wrapping\Repositories\Dashboard;

use Modules\Wrapping\Entities\Gift;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Core\Traits\CoreTrait;
use Modules\Core\Traits\SyncRelationModel;

class GiftRepository
{
    use SyncRelationModel, CoreTrait;

    protected $gift;

    function __construct(Gift $gift)
    {
        $this->gift = $gift;
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $gifts = $this->gift->orderBy($order, $sort)->get();
        return $gifts;
    }

    public function findById($id)
    {
        $gift = $this->gift->withDeleted()->find($id);
        return $gift;
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {

            $data = [
                // 'image' => $request->image ? path_without_domain($request->image) : url(config('setting.logo')),
                'status' => $request->status ? 1 : 0,
                'price' => $request->price,
                'qty' => $request->qty,
                'sku' => $request->sku,
                "size" => $request->size,
                "title" => $request->title
            ];

            if (!is_null($request->image)) {
                $imgName = $this->uploadImage(public_path(config('core.config.gifts_img_path')), $request->image);
                $data['image'] = config('core.config.gifts_img_path') . '/' . $imgName;
            } else {
                $data['image'] = url(config('setting.logo'));
            }

            $gift = $this->gift->create($data);

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
        $gift = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelete($gift) : null;

        try {

            $data = [
                // 'image' => $request->image ? path_without_domain($request->image) : $gift->image,
                'status' => $request->status ? 1 : 0,
                'price' => $request->price,
                'qty' => $request->qty,
                'sku' => $request->sku,
                "size" => $request->size,
                "title" => $request->title

            ];

            if ($request->image) {
                if (!empty($gift->image) && !in_array($gift->image, config('core.config.special_images'))) {
                    File::delete($gift->image); ### Delete old image
                }
                $imgName = $this->uploadImage(public_path(config('core.config.gifts_img_path')), $request->image);
                $data['image'] = config('core.config.gifts_img_path') . '/' . $imgName;
            } else {
                $data['image'] = $gift->image;
            }

            $gift->update($data);


            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelete($model)
    {
        return $model->restore();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            if ($model) {
                if (!empty($model->image) && !in_array($model->image, config('core.config.special_images'))) {
                    File::delete($model->image); ### Delete old image
                }

                if ($model->trashed()) :
                    $model->forceDelete();
                else :
                    $model->delete();
                endif;
            }

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

    public function QueryTable($request)
    {
        $query = $this->gift->query();

        $query->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->input('search.value') . '%');
                $query->orWhere('slug', 'like', '%' . $request->input('search.value') . '%');
            });
        });

        return $this->filterDataTable($query, $request);
    }

    public function filterDataTable($query, $request)
    {
        // Search Categories by Created Dates
        if (isset($request['req']['from']) && $request['req']['from'] != '')
            $query->whereDate('created_at', '>=', $request['req']['from']);

        if (isset($request['req']['to']) && $request['req']['to'] != '')
            $query->whereDate('created_at', '<=', $request['req']['to']);

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'only')
            $query->onlyDeleted();

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'with')
            $query->withDeleted();

        if (isset($request['req']['status']) && $request['req']['status'] == '1')
            $query->active();

        if (isset($request['req']['status']) && $request['req']['status'] == '0')
            $query->unactive();

        return $query;
    }
}
