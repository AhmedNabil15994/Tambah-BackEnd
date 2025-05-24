<?php

namespace Modules\POS\Repositories\Dashboard;

use Modules\POS\Entities\Barcode as Model;
use Illuminate\Support\Facades\DB;

class BarcodeRepository
{
    protected $model;

    function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll($order = 'id', $sort = 'asc')
    {
        $tags = $this->model->active()->orderBy($order, $sort)->get();
        return $tags;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        $data = $this->model->orderBy($order, $sort)->active()
            ->select(DB::raw('CONCAT(name, ", ", COALESCE(description, "")) as name, id'))
            ->get();


        return $data;
    }

    public function findById($id)
    {
        $model = $this->model->find($id);
        return $model;
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {


            $model = $this->model->create(array_merge($request->validated(), [
                "status"   => $request->status ?  1 : 0,
            ]));



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
        $model = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelete($model) : null;

        try {

            $model->update(
                array_merge($request->validated(), [
                    "status"   => $request->status ?  1 : 0,
                    "is_continuous"   => $request->is_continuous ?  1 : 0,
                ])
            );



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

    public function translateTable($model, $request)
    {
        foreach ($request['title'] as $locale => $value) {
            $model->translateOrNew($locale)->title = $value;
        }
        $model->save();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);

            if ($model->trashed()) :
                $model->forceDelete();
            else :
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

    public function QueryTable($request)
    {
        $query = $this->model;

        $query->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere('name', 'like', '%' . $request->input('search.value') . '%');
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
