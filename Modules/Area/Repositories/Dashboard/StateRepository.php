<?php

namespace Modules\Area\Repositories\Dashboard;

use Modules\Area\Entities\{State, City};
use Hash;
use DB;

class StateRepository
{

    function __construct(State $state, City $city)
    {
        $this->state = $state;
        $this->city = $city;
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $countrys = $this->country->orderBy($order, $sort)->get();
        return $countrys;
    }

    public function getAllByCityId($cityId)
    {
        $states = $this->state->where('city_id', $cityId)->get();
        return $states;
    }

    public function findById($id)
    {
        $state = $this->state->withDeleted()->find($id);
        return $state;
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {

            $state = $this->state->create([
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'status' => $request->status ? 1 : 0,
                "title" => $request->title,
                "name" => $request->title,
            ]);

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

        $state = $this->findById($id);
        $request->restore ? $this->restoreSoftDelte($state) : null;

        try {

            $state->update([
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'status' => $request->status ? 1 : 0,
                "title" => $request->title,
                "name" => $request->title,
            ]);



            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelte($model)
    {
        $model->restore();
    }


    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $city = $this->findById($id);

            if ($city->trashed()) :
                $city->forceDelete();
            else :
                $city->delete();
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
        $query = $this->state->with(['city'])->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->input('search.value') . '%');
            });
            $query->orWhereHas('city', function ($query) use ($request) {

                $query->where('title->' . locale(), 'like', '%' . $request->input('search.value') . '%');
                $query->orWhereHas('country', function ($query) use ($request) {

                    $query->where('title->' . locale(), 'like', '%' . $request->input('search.value') . '%');
                });
            });
        });

        $query = $this->filterDataTable($query, $request);

        return $query;
    }

    public function filterDataTable($query, $request)
    {
        // Search Migration by Created Dates

        if (isset($request['req']['country_id']) && $request['req']['country_id'] != '') {

            $query->where('country_id', $request['req']['country_id']);
        }

        if (isset($request['req']['state_id']) && $request['req']['state_id'] != '') {

            $query->where('state_id', $request['req']['state_id']);
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

        return $query;
    }


    public function getChildAreaByParent($request, $order = 'id', $sort = 'desc')
    {
        $query = null;
        if ($request->type == 'city') {
            $query = $this->city->where('country_id', $request->parent_id)->orderBy($order, $sort)->get();
        } elseif ($request->type == 'state') {
            $query = $this->state->where('state_id', $request->parent_id)->orderBy($order, $sort)->get();
        }

        return $query;
    }
}
