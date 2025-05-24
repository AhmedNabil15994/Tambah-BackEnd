<?php

namespace Modules\Coupon\Http\Requests\Dashboard;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod()) {
            // handle creates
            case 'post':
            case 'POST':

                return [
                    'title.*' => 'nullable',
                    'discount_type' => 'required',
                    'code' => 'required|unique:coupons,code',
                    'coupon_flag' => 'nullable|in:code,vendors,categories,products',
                    'discount_value' => 'required_if:discount_type,==,value',
                    'discount_percentage' => 'required_if:discount_type,==,percentage',
                    'start_at' => request()->add_dates ? 'required|date_format:Y-m-d|after_or_equal:' . Carbon::now()->format('Y-m-d') : '',
                    'expired_at' => request()->add_dates ? 'required|date_format:Y-m-d|after:start_at' : '',
                    'user_type' => 'required|array|in:user,guest',
                    'user_max_uses' => 'nullable|numeric|min:1',
                ];

            //handle updates
            case 'put':
            case 'PUT':
                return [
                    'title.*' => 'nullable',
                    'discount_type' => 'required',
                    'code' => 'required|unique:coupons,code,' . $this->id,
                    'coupon_flag' => 'nullable|in:code,vendors,categories,products',
                    'discount_value' => 'required_if:discount_type,==,value',
                    'discount_percentage' => 'required_if:discount_type,==,percentage',
                    'start_at' => request()->add_dates ? 'required|date_format:Y-m-d|after_or_equal:' . Carbon::now()->format('Y-m-d') : '',
                    'expired_at' => request()->add_dates ? 'required|date_format:Y-m-d|after:start_at' : '',
                    'user_type' => 'required|array|in:user,guest',
                    'user_max_uses' => 'nullable|numeric|min:1',
                ];
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        $v = [
            'discount_type.required' => __('coupon::dashboard.coupons.validation.discount_type.required'),
            'code.required' => __('coupon::dashboard.coupons.validation.code.required'),
            'code.unique' => __('coupon::dashboard.coupons.validation.code.unique'),
            'coupon_flag.in' => __('coupon::dashboard.coupons.validation.coupon_flag.in'),

            'vendor_ids.required_if' => __('coupon::dashboard.coupons.validation.vendor_ids.required_if'),
            'category_ids.required_if' => __('coupon::dashboard.coupons.validation.category_ids.required_if'),
            'product_ids.required_if' => __('coupon::dashboard.coupons.validation.product_ids.required_if'),
            'discount_value.required_if' => __('coupon::dashboard.coupons.validation.discount_value.required_if'),
            'discount_percentage.required_if' => __('coupon::dashboard.coupons.validation.discount_percentage.required_if'),

            'start_at.required' => __('coupon::dashboard.coupons.validation.start_at.required'),
            'start_at.date_format' => __('coupon::dashboard.coupons.validation.start_at.date_format'),
            'start_at.after_or_equal' => __('coupon::dashboard.coupons.validation.start_at.after_or_equal'),
            'expired_at.required' => __('coupon::dashboard.coupons.validation.expired_at.required'),
            'expired_at.date_format' => __('coupon::dashboard.coupons.validation.expired_at.date_format'),
            'expired_at.after' => __('coupon::dashboard.coupons.validation.expired_at.after'),
        ];
        return $v;
    }
}
