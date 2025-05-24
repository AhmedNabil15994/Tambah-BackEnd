<?php

namespace Modules\Coupon\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Coupon\Entities\Coupon;

class CouponRepository
{
    protected $coupon;

    function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    public function findById($id)
    {
        $coupon = $this->coupon->with('users', 'categories', 'products')->withDeleted()->find($id);
        return $coupon;
    }

    /**
     * @throws \Exception
     */
    public function create($request)
    {

        DB::beginTransaction();

        try {
            $flag = $request->coupon_flag . '_ids';
            $data = [
                'code' => $request->code <> null ? $request->code : str_random(5),
                'discount_type' => $request->discount_type,
                'max_discount_percentage_value' => $request->max_discount_percentage_value ?? null,
                'max_users' => $request->max_users,
                'user_max_uses' => $request->user_max_uses,
                'start_at' => $request->start_at,
                'expired_at' => $request->expired_at,
                'custom_type' => $request->custom_type,
                'status' => $request->status ? 1 : 0,
                'free_delivery' => $request->free_delivery ? 1 : 0,
                'flag' => $request->coupon_flag && $request->$flag && count($request->$flag) ? $request->coupon_flag : null,
                "title"=> $request->title,
                "user_type"=> $request->user_type,
            ];
            if ($request->discount_type == 'value') {
                $data['discount_percentage'] = null;
                $data['discount_value'] = $request->discount_value;
            } elseif ($request->discount_type == 'percentage') {
                $data['discount_percentage'] = $request->discount_percentage;
                $data['discount_value'] = null;
            } else {
                $data['discount_percentage'] = null;
                $data['discount_value'] = null;
            }
            $coupon = $this->coupon->create($data);

            if ($request->coupon_flag == 'categories' && $request['categories_ids'])
                $this->categoriesOfCouponSync($coupon, $request);

            if ($request->coupon_flag == 'products' && $request['products_ids'])
                $this->productsOfCouponSync($coupon, $request);

            if ($request['user_ids'])
                $this->usersOfCouponSync($coupon, $request);

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

        $coupon = $this->findById($id);
        $request->restore ? $this->restoreSoftDelete($coupon) : null;

        try {
            $flag = $request->coupon_flag . '_ids';

            $data = [
                'code' => $request->code,
                'discount_type' => $request->discount_type,
                'max_discount_percentage_value' => $request->max_discount_percentage_value ?? null,
                'max_users' => $request->max_users,
                'user_max_uses' => $request->user_max_uses,
                'start_at' => $request->start_at,
                'expired_at' => $request->expired_at,
                'custom_type' => $request->custom_type,
                'status' => $request->status ? 1 : 0,
                'free_delivery' => $request->free_delivery ? 1 : 0,
                'flag' => $request->coupon_flag && $request->$flag && count($request->$flag) ? $request->coupon_flag : null,
                "title"=> $request->title,
                "user_type"=> $request->user_type,
            ];

            if(!$request->add_dates){
                $data['start_at'] = null;
                $data['expired_at'] = null;
            }

            if ($request->discount_type == 'value') {
                $data['discount_percentage'] = null;
                $data['discount_value'] = $request->discount_value;
            } elseif ($request->discount_type == 'percentage') {
                $data['discount_percentage'] = $request->discount_percentage;
                $data['discount_value'] = null;
            } else {
                $data['discount_percentage'] = null;
                $data['discount_value'] = null;
            }
            $coupon->update($data);


            if ($request->coupon_flag == 'categories') {

                $this->categoriesOfCouponSync($coupon, $request);
                $coupon->products()->detach();

            }elseif ($request->coupon_flag == 'products') {

                $this->productsOfCouponSync($coupon, $request);
                $coupon->categories()->detach();
            }else{
                $coupon->products()->detach();
                $coupon->categories()->detach();
            }

            $this->usersOfCouponSync($coupon, $request);

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
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            $model = $this->findById($id);

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

    public function usersOfCouponSync($model, $request)
    {
        $model->users()->sync($request['user_ids']);
        return true;

        /*foreach ($request['user_ids'] as $key => $value) {
            $model->users()->updateOrCreate([
                'user_id' => $value,
            ]);
        }
        return true;*/
    }

    public function categoriesOfCouponSync($model, $request)
    {
        $model->categories()->sync($request['categories_ids']);
        return true;

        /*foreach ($request['category_ids'] as $key => $value) {
            $model->categories()->updateOrCreate([
                'category_id' => $value,
            ]);
        }
        return true;*/
    }

    public function ipackagesOfCouponSync($model, $request)
    {
        foreach ($request['ipackage_ids'] as $key => $value) {
            $model->ipackages()->updateOrCreate([
                'ipackage_id' => $value,
            ]);
        }
        return true;
    }

    public function productsOfCouponSync($model, $request)
    {
        $model->products()->sync($request['products_ids']);
        return true;

        /*foreach ($request['product_ids'] as $key => $value) {
            $model->products()->updateOrCreate([
                'product_id' => $value,
            ]);
        }
        return true;*/
    }

    public function QueryTable($request)
    {
        $query = $this->coupon->where(function ($query) use ($request) {
            $query->where('id', 'like', '%' . $request->input('search.value') . '%');
        });

        $query = $this->filterDataTable($query, $request);

        return $query;
    }

    public function filterDataTable($query, $request)
    {
        // SEARCHING INPUT DATATABLE
        if ($request->input('search.value') != null) {

            $query = $query->where(function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            });

        }

        // FILTER
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
