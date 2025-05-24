<?php

namespace Modules\Order\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Order\Entities\OrderStatus;

class OrderStatusRepository
{
    protected $orderStatus;

    public function __construct(OrderStatus $orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $orderStatuses = $this->orderStatus->orderBy($order, $sort)->get();
        return $orderStatuses;
    }

    public function getAllFinalStatus($order = 'id', $sort = 'desc')
    {
        $orderStatuses = $this->orderStatus->finalStatus()->orderBy($order, $sort)->get();
        return $orderStatuses;
    }

    public function findById($id)
    {
        $orderStatus = $this->orderStatus->find($id);
        return $orderStatus;
    }

    public function findByFlag($flag)
    {
        return $this->orderStatus->where('flag', $flag)->first();
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            $data = [
                'flag' => $request->flag,
                'sort' => $request->sort,
                'color_label' => \GuzzleHttp\json_encode($this->setOrderStatusColorValue($request->color_label)),
                'is_success' => $request->is_success,
                "title" => $request->title,
            ];
            $orderStatus = $this->orderStatus->create($data);

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

        $orderStatus = $this->findById($id);
        $restore = $request->restore ? $this->restoreSoftDelte($orderStatus) : null;

        try {
            $data = [
                'color_label' => \GuzzleHttp\json_encode($this->setOrderStatusColorValue($request->color_label)),
                'is_success' => $request->is_success,
                "title" => $request->title,
                'sort' => $request->sort,
            ];

            $newFlag = Str::slug($request->title['en'], '_');
            if (!in_array($orderStatus->flag, ['pending', 'cancelled', 'refund', 'success', 'failed', 'delivered', 'new_order'])) {
                $data['flag'] = $newFlag;
            }

            $orderStatus->update($data);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function setOrderStatusColorValue($colorLabel = '')
    {
        $color = [];
        $color['text'] = $colorLabel;
        switch ($colorLabel) {
            case "danger":
                $color['value'] = '#F8D7DA';
                break;
            case "success":
                $color['value'] = '#D4EDDA';
                break;
            case "warning":
                $color['value'] = '#FCF3CD';
                break;
            case "info":
                $color['value'] = '#D1EBF1';
                break;
            default:
                $color['value'] = '#000000';
        }
        return $color;
    }

    public function restoreSoftDelte($model)
    {
        $model->restore();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $orderStatus = $this->findById($id);

            if (!in_array($orderStatus->flag, ['pending', 'cancelled', 'refund', 'success', 'failed', 'delivered', 'new_order'])) {
                $orderStatus->delete();
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

    public function updateColorInSettings($items)
    {
        try {

            foreach ($items as $k => $value) {
                $this->orderStatus->where('flag', $k)->update([
                    'color' => $value ?? null,
                ]);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function QueryTable($request)
    {
        $query = $this->orderStatus->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            $query->where('title', 'like', '%' . $request->input('search.value') . '%');
        });

        $query = $this->filterDataTable($query, $request);

        return $query;
    }

    public function filterDataTable($query, $request)
    {
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

        return $query;
    }
}
