<?php

namespace Modules\Company\Repositories\Dashboard;

use Modules\Company\Entities\DeliveryCharge;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DeliveryChargeRepository
{

    function __construct(DeliveryCharge $deliveryCharge)
    {
        $this->deliveryCharge = $deliveryCharge;
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $deliveryCharges = $this->deliveryCharge->orderBy($order, $sort)->get();
        return $deliveryCharges;
    }

    public function findById($id)
    {
        $deliveryCharge = $this->deliveryCharge->find($id);
        return $deliveryCharge;
    }

    public function findDeliveryCharge($companyId, $stateId)
    {
        $deliveryCharge = $this->deliveryCharge
            ->where('company_id', $companyId)
            ->where('state_id', $stateId)
            ->first();

        return $deliveryCharge;
    }

    public function update($request, $company)
    {
        DB::beginTransaction();

        try {
            foreach ($request['state'] as $key => $state) {

                if ($request['delivery'][$state] != null) {
                    $company->deliveryCharge()->updateOrCreate([

                        'company_id' => $company->id,
                        'state_id' => $state,
                    ],[
                        'state_id' => $state,
                        'delivery' => $request['delivery'][$state],
                        'min_order_amount' => $request['min_order_amount'][$state],
                        'delivery_time' => isset($request['delivery_time'][$state]) ? $request['delivery_time'][$state] : 60,
                        'status' => isset($request['status'][$state]) && $request['status'][$state] == 'on' ? 1 : 0,
                    ]);
                }
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);
            $model->delete();

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
        $query = $this->deliveryCharge->where(function ($query) use ($request) {

            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->orWhere('delivery', 'like', '%' . $request->input('search.value') . '%');

        });

        $query = $this->filterDataTable($query, $request);

        return $query;
    }

    public function filterDataTable($query, $request)
    {
        // Search Pages by Created Dates
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
