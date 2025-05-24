<?php

namespace Modules\Order\Http\Requests\WebService;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Catalog\Entities\Payment;
use Modules\Company\Entities\DeliveryCharge;

class CreateOrderRequest extends FormRequest
{
    public function rules()
    {
        /* if ($this->address_type == 'guest_address') {
        $rules = [
        'address.username' => 'required|string',
        'address.email' => 'required|email',
        'address.state_id' => 'required|numeric',
        'address.mobile' => 'required|string',
        'address.block' => 'required|string',
        'address.street' => 'required|string',
        'address.building' => 'nullable|string',
        'address.address' => 'nullable|string',
        'address.address_title' => 'nullable|string',
        ];
        } elseif ($this->address_type == 'selected_address') {
        $rules = [
        'address.selected_address_id' => 'nullable',
        ];
        } else {
        if (!$this->from_cashier) {
        $rules = [
        'address_type' => 'required|in:guest_address,selected_address',
        ];
        }
        } */

        if (!$this->from_cashier) {
            $rules = [
                'address_type' => 'required|in:guest_address,selected_address',
            ];
        }

        if (auth('api')->guest()) {
            $rules = [
                'address.username' => 'required|string',
                'address.email' => 'required|email',
                'address.state_id' => 'required|numeric',
                'address.mobile' => 'required|string|min:8',
                // 'address.mobile' => 'required|string|min:8|max:8',
                'address.block' => 'required|string',
                'address.street' => 'required|string',
                'address.building' => 'nullable|string',
                'address.address' => 'nullable|string',
                'address.address_title' => 'nullable|string',
            ];
        } elseif (auth('api')->check()) {
            $rules = [
                'address.selected_address_id' => 'nullable',
            ];
        }

        // $rules['user_id'] = 'nullable|exists:users,id';
        // $rules['payment'] = 'required|in:cash,online';
        $rules['payment'] = 'required';
        /* $rules['shipping_company.availabilities.day_code'] = 'nullable';
        $rules['shipping_company.availabilities.day'] = 'nullable'; */

        return $rules;
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        $messages = [
            'address_type.required' => __('order::api.address.validations.address_type.required'),
            'address_type.in' => __('order::api.address.validations.address_type.in'),
            'selected_address_id.required' => __('order::api.address.validations.selected_address_id.required'),

            'address.username.string' => __('order::api.address.validations.username.string'),
            'address.email.email' => __('order::api.address.validations.email.email'),
            'address.state_id.required' => __('order::api.address.validations.state_id.required'),
            'address.state_id.numeric' => __('order::api.address.validations.state_id.numeric'),
            'address.mobile.required' => __('order::api.address.validations.mobile.required'),
            'address.mobile.numeric' => __('order::api.address.validations.mobile.numeric'),
            'address.mobile.digits_between' => __('order::api.address.validations.mobile.digits_between'),
            'address.mobile.min' => __('order::api.address.validations.mobile.digits_between'),
            'address.mobile.max' => __('order::api.address.validations.mobile.max'),
            'address.address.required' => __('order::api.address.validations.address.required'),
            'address.address.string' => __('order::api.address.validations.address.string'),
            'address.address.min' => __('order::api.address.validations.address.min'),
            'address.block.required' => __('order::api.address.validations.block.required'),
            'address.block.string' => __('order::api.address.validations.block.string'),
            'address.street.required' => __('order::api.address.validations.street.required'),
            'address.street.string' => __('order::api.address.validations.street.string'),
            'address.building.required' => __('order::api.address.validations.building.required'),
            'address.building.string' => __('order::api.address.validations.building.string'),

            'user_id.exists' => __('order::api.orders.validations.user_id.exists'),
            'payment.required' => __('order::api.payment.validations.required'),
            'payment.in' => __('order::api.payment.validations.in') . ' cash,online',

            'shipping_company.availabilities.day_code.required' => __('order::api.shipping_company.validations.day_code.required'),
            'shipping_company.availabilities.day.required' => __('order::api.shipping_company.validations.day.required'),
        ];

        return $messages;
    }

    public function withValidator($validator)
    {
        if (auth('api')->check()) {
            $userToken = auth('api')->user()->id;
        } else {
            $userToken = $this->user_id ?? null;
        }

        $validator->after(function ($validator) use ($userToken) {

            if (auth('api')->guest() && is_null($this->user_id)) {
                return $validator->errors()->add(
                    'user_id',
                    __('order::api.orders.validations.user_id.required')
                );
            }

            if (count(getCartContent($userToken)) <= 0) {
                return $validator->errors()->add(
                    'cart',
                    __('order::api.orders.validations.cart.required')
                );
            }

            $companyDeliveryFees = getCartConditionByName($userToken, 'company_delivery_fees');

            // if (!$this->from_cashier) {
            if (is_null($companyDeliveryFees)) {
                return $validator->errors()->add(
                    'company_delivery_fees',
                    __('order::api.orders.validations.company_delivery_fees.required')
                );
            }
            // }

            if (auth('api')->check() && $companyDeliveryFees != null && empty($companyDeliveryFees->getAttributes()['address_id'])) {
                return $validator->errors()->add(
                    'address_id',
                    __('order::api.orders.validations.address_id.required')
                );
            }

            $companyId = config('setting.other.shipping_company') ?? 0;
            $stateId = $companyDeliveryFees->getAttributes()['state_id'] ?? null;
            $delivery = DeliveryCharge::active()->where('state_id', $stateId)->where('company_id', $companyId)->first();

            ### Start Checking minimum order validation ###
            if (!$delivery) {
                return $validator->errors()->add(
                    'delivery_charge',
                    __('order::api.orders.validations.delivery_charge.not_found')
                );
            } else {
                if (!is_null($delivery->min_order_amount) && getCartTotal($userToken) < floatval($delivery->min_order_amount)) {
                    return $validator->errors()->add(
                        'min_order_amount',
                        __('order::api.orders.validations.min_order_amount_greater_than_cart_total') . ': ' . number_format($delivery->min_order_amount, 3)
                    );
                }
            }
            ### End Checking minimum order validation

            /* if (!in_array($this->payment, Payment::where('show_in_pos',1)->pluck('code')->toArray() ?? []) || ($this->payment == 'cash' && !in_array($this->payment, config('setting.other.supported_payments') ?? [])) || !in_array('online', config('setting.other.supported_payments') ?? [])) {
            return $validator->errors()->add(
            'payment',
            __('order::frontend.orders.index.alerts.payment_not_supported_now')
            );
            } */

            $checkOnlinePaymentCondition = $this->payment != 'cash' && !in_array('online', config('setting.other.supported_payments') ?? []);
            if (config('setting.payment_gateway.' . $this->payment . '.status') != 'on') {
                return $validator->errors()->add(
                    'payment',
                    __('order::frontend.orders.index.alerts.payment_not_supported_now')
                );
            }
        });
        return true;
    }
}
