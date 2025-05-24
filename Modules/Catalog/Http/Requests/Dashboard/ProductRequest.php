<?php

namespace Modules\Catalog\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {

        // dd( $this->all() );
        switch ($this->getMethod()) {

            // handle creates
            case 'post':
            case 'POST':

                $result = [
                    'title.*' => 'required',
//                    'title.*' => 'required|unique:product_translations,title',
                    'price' => 'required|numeric|min:0',
//                    'qty' => 'required|integer|min:1',
                    'variation_price.*' => 'sometimes|required',
                    'variation_qty.*' => 'sometimes|required',
                    'variation_status.*' => 'sometimes|required',
                    'variation_sku.*' => 'nullable',
                    'sku' => 'nullable',
                    'category_id' => 'required',

                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

                    /*'offer_price' => 'sometimes|required|numeric',
                    'start_at' => 'sometimes|required|date',
                    'end_at' => 'sometimes|required|date',*/

                    /*'v_offers.*.offer_price' => 'required|numeric',
                    'v_offers.*.start_at' => 'sometimes|required|date',
                    'v_offers.*.end_at' => 'sometimes|required|date',*/

                    'shipment.width' => 'sometimes|nullable|numeric',
                    'shipment.length' => 'sometimes|nullable|numeric',
                    'shipment.weight' => 'sometimes|nullable|numeric',
                    'shipment.height' => 'sometimes|nullable|numeric',
                    'vshipment.*.width' => 'sometimes|nullable|numeric',
                    'vshipment.*.length' => 'sometimes|nullable|numeric',
                    'vshipment.*.weight' => 'sometimes|nullable|numeric',
                    'vshipment.*.height' => 'sometimes|nullable|numeric',

                    'images' => 'nullable|array',
                    'tags' => 'nullable|array',
                    'ages' => 'nullable|array|exists:ages,id',
                    'search_keywords' => 'nullable|array',

                    'brand_id' => 'nullable|exists:brands,id',
                    'vendor_id' => 'required|exists:vendors,id',
                    'for_boys_girls' => 'required|in:both,boys,girls',
                ];

                if ($this->manage_qty == 'limited') {
                    $result['qty'] = 'required|integer|min:0';
                }

                if ($this->offer_status) {
                    $result['offer_type'] = 'required|in:amount,percentage';
                    // $result['offer_price'] = 'required_if:offer_status,on|numeric';
                    $result['offer_price'] = 'required_if:offer_type,amount';
                    $result['offer_percentage'] = 'required_if:offer_type,percentage';
                    $result['start_at'] = 'required_if:offer_status,on|date';
                    $result['end_at'] = 'required_if:offer_status,on|date';
                }

                if ($this->v_offers) {
                    $result['v_offers.*.offer_price'] = 'required_if:v_offers.*.status,on|numeric';
                    $result['v_offers.*.start_at'] = 'required_if:v_offers.*.status,on|date';
                    $result['v_offers.*.end_at'] = 'required_if:v_offers.*.status,on|date';
                }

                if (isset($this->images) && !empty($this->images)) {
                    foreach ($this->images as $k => $img) {
                        $result['images.' . $k] = 'mimes:jpeg,png,jpg,gif,svg|max:1024';
                    }
                }

                if (isset($this->v_images) && !empty($this->v_images)) {
                    $result['v_images.*'] = 'mimes:jpeg,png,jpg,gif,svg|max:1024';
                }

                if (isset($this->_v_images) && !empty($this->_v_images)) {
                    $result['_v_images.*'] = 'mimes:jpeg,png,jpg,gif,svg|max:1024';
                }

                return $result;

            //handle updates
            case 'put':
            case 'PUT':
                $res = [
//                    'title.*' => 'required|unique:product_translations,title,' . $this->id . ',product_id',
                    // 'price' => 'required|numeric|min:0',
                    //                    'qty' => 'required|integer|min:1',
                    'variation_price.*' => 'sometimes|required',
                    'variation_qty.*' => 'sometimes|required',
                    'variation_status.*' => 'sometimes|required',
                    'variation_sku.*' => 'nullable',

                    '_variation_price.*' => 'sometimes|required',
                    '_variation_qty.*' => 'sometimes|required',
                    '_variation_status.*' => 'sometimes|required',
                    '_variation_sku.*' => 'nullable',
                    '_vshipment.*.width' => 'sometimes|nullable|numeric',
                    '_vshipment.*.length' => 'sometimes|nullable|numeric',
                    '_vshipment.*.weight' => 'sometimes|nullable|numeric',
                    '_vshipment.*.height' => 'sometimes|nullable|numeric',
                    'sku' => 'nullable',

                    /*'offer_price' => 'sometimes|required|numeric',
                    'start_at' => 'sometimes|required|date',
                    'end_at' => 'sometimes|required|date',*/

                    /*'v_offers.*.offer_price' => 'sometimes|required|numeric',
                    'v_offers.*.start_at' => 'sometimes|required|date',
                    'v_offers.*.end_at' => 'sometimes|required|date',
                    '_v_offers.*.offer_price' => 'required|numeric',
                    '_v_offers.*.start_at' => 'sometimes|required|date',
                    '_v_offers.*.end_at' => 'sometimes|required|date',*/

                    'shipment.width' => 'sometimes|nullable|numeric',
                    'shipment.length' => 'sometimes|nullable|numeric',
                    'shipment.weight' => 'sometimes|nullable|numeric',
                    'shipment.height' => 'sometimes|nullable|numeric',
                    'vshipment.*.width' => 'sometimes|nullable|numeric',
                    'vshipment.*.length' => 'sometimes|nullable|numeric',
                    'vshipment.*.weight' => 'sometimes|nullable|numeric',
                    'vshipment.*.height' => 'sometimes|nullable|numeric',

                    'tags' => 'nullable|array',
                    'ages' => 'nullable|array|exists:ages,id',
                    'search_keywords' => 'nullable|array',

                    'brand_id' => 'nullable|exists:brands,id',
                    'vendor_id' => 'required|exists:vendors,id',
                    'for_boys_girls' => 'required|in:both,boys,girls',
                ];

                if (auth()->user()->can('edit_products_title')) {
                    $res['title.*'] = 'required';
                }

                if (auth()->user()->can('edit_products_description')) {
                    $res['description.*'] = 'nullable';
                    $res['short_description.*'] = 'nullable';
                }

                if (auth()->user()->can('edit_products_category')) {
                    $res['category_id'] = 'required';
                }

                if (auth()->user()->can('edit_products_qty')) {

                    if ($this->manage_qty == 'limited') {
                        $res['qty'] = 'required|integer|min:0';
                    }

                    $res['price'] = 'required|numeric|min:0';
                }

                if (auth()->user()->can('edit_products_image')) {
                    $res['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
                }

                if ($this->offer_status) {
                    $res['offer_type'] = 'required|in:amount,percentage';
                    // $res['offer_price'] = 'required_if:offer_status,on|numeric';
                    $res['offer_price'] = 'required_if:offer_type,amount';
                    $res['offer_percentage'] = 'required_if:offer_type,percentage';
                    $res['start_at'] = 'required_if:offer_status,on|date';
                    $res['end_at'] = 'required_if:offer_status,on|date';
                }

                if ($this->v_offers) {
                    $res['v_offers.*.offer_price'] = 'required_if:v_offers.*.status,on|numeric';
                    $res['v_offers.*.start_at'] = 'required_if:v_offers.*.status,on|date';
                    $res['v_offers.*.end_at'] = 'required_if:v_offers.*.status,on|date';
                }

                if ($this->_v_offers) {
                    $res['_v_offers.*.offer_price'] = 'required_if:_v_offers.*.status,on|numeric';
                    $res['_v_offers.*.start_at'] = 'required_if:_v_offers.*.status,on|date';
                    $res['_v_offers.*.end_at'] = 'required_if:_v_offers.*.status,on|date';
                }

                if (auth()->user()->can('edit_products_gallery')) {
                    $res['images'] = 'nullable|array';

                    if (isset($this->images) && !empty($this->images)) {
                        foreach ($this->images as $k => $img) {
                            $res['images.' . $k] = 'mimes:jpeg,png,jpg,gif,svg|max:1024';
                        }
                    }
                }

                if (isset($this->v_images) && !empty($this->v_images)) {
                    $res['v_images.*'] = 'mimes:jpeg,png,jpg,gif,svg|max:1024';
                }

                if (isset($this->_v_images) && !empty($this->_v_images)) {
                    $res['_v_images.*'] = 'mimes:jpeg,png,jpg,gif,svg|max:1024';
                }

                return $res;
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
            'price.required' => __('catalog::dashboard.products.validation.price.required'),
            'price.numeric' => __('catalog::dashboard.products.validation.price.numeric'),
            'sku.required' => __('catalog::dashboard.products.validation.sku.required'),
            'variation_price.*.required' => __('catalog::dashboard.products.validation.variation_price.required'),
            'variation_qty.*.required' => __('catalog::dashboard.products.validation.variation_qty.required'),
            'variation_status.*.required' => __('catalog::dashboard.products.validation.variation_status.required'),
            'variation_sku.*.required' => __('catalog::dashboard.products.validation.variation_sku.required'),

            'qty.required' => __('catalog::dashboard.products.validation.qty.required'),
            'qty.integer' => __('catalog::dashboard.products.validation.qty.integer'),
            'qty.numeric' => __('catalog::dashboard.products.validation.qty.numeric'),
            'qty.min' => __('catalog::dashboard.products.validation.qty.min') . ' 0',

            'category_id.required' => __('catalog::dashboard.products.validation.category_id.required'),

            '_variation_price.*.required' => __('catalog::dashboard.products.validation.variation_price.required'),
            '_variation_qty.*.required' => __('catalog::dashboard.products.validation.variation_qty.required'),
            '_variation_status.*.required' => __('catalog::dashboard.products.validation.variation_status.required'),
            '_variation_sku.*.required' => __('catalog::dashboard.products.validation.variation_sku.required'),

            'offer_type.required' => __('catalog::dashboard.products.validation.offer_type.required'),
            'offer_type.in' => __('catalog::dashboard.products.validation.offer_type.in') . ' : amount,percentage',
            'offer_price.required_if' => __('catalog::dashboard.products.validation.offer_price.required'),
            'offer_price.numeric' => __('catalog::dashboard.products.validation.offer_price.numeric'),
            'offer_percentage.required_if' => __('catalog::dashboard.products.validation.offer_percentage.required'),
            'offer_percentage.numeric' => __('catalog::dashboard.products.validation.offer_percentage.numeric'),

            'start_at.required_if' => __('catalog::dashboard.products.validation.start_at.required'),
            'start_at.date' => __('catalog::dashboard.products.validation.start_at.date'),
            'end_at.required_if' => __('catalog::dashboard.products.validation.end_at.required'),
            'end_at.date' => __('catalog::dashboard.products.validation.end_at.date'),

            'v_offers.*.offer_price.numeric' => __('catalog::dashboard.products.validation.offer_price.numeric'),
            'v_offers.*.offer_price.required_if' => __('catalog::dashboard.products.validation.offer_price.required'),
            'v_offers.*.start_at.required_if' => __('catalog::dashboard.products.validation.start_at.required'),
            'v_offers.*.start_at.date' => __('catalog::dashboard.products.validation.start_at.date'),
            'v_offers.*.end_at.required_if' => __('catalog::dashboard.products.validation.end_at.required'),
            'v_offers.*.end_at.date' => __('catalog::dashboard.products.validation.end_at.date'),

            '_v_offers.*.offer_price.numeric' => __('catalog::dashboard.products.validation.offer_price.numeric'),
            '_v_offers.*.offer_price.required_if' => __('catalog::dashboard.products.validation.offer_price.required'),
            '_v_offers.*.start_at.required_if' => __('catalog::dashboard.products.validation.start_at.required'),
            '_v_offers.*.start_at.date' => __('catalog::dashboard.products.validation.start_at.date'),
            '_v_offers.*.end_at.required_if' => __('catalog::dashboard.products.validation.end_at.required'),
            '_v_offers.*.end_at.date' => __('catalog::dashboard.products.validation.end_at.date'),

            'shipment.width.required' => __('catalog::dashboard.products.validation.width.required'),
            'shipment.width.numeric' => __('catalog::dashboard.products.validation.width.numeric'),
            'shipment.length.required' => __('catalog::dashboard.products.validation.length.required'),
            'shipment.length.numeric' => __('catalog::dashboard.products.validation.length.numeric'),
            'shipment.weight.required' => __('catalog::dashboard.products.validation.weight.required'),
            'shipment.weight.numeric' => __('catalog::dashboard.products.validation.weight.numeric'),
            'shipment.height.required' => __('catalog::dashboard.products.validation.height.required'),
            'shipment.height.numeric' => __('catalog::dashboard.products.validation.height.numeric'),
            'vshipment.*.width.required' => __('catalog::dashboard.products.validation.width.required'),
            'vshipment.*.width.numeric' => __('catalog::dashboard.products.validation.width.numeric'),
            'vshipment.*.length.required' => __('catalog::dashboard.products.validation.length.required'),
            'vshipment.*.length.numeric' => __('catalog::dashboard.products.validation.length.numeric'),
            'vshipment.*.weight.required' => __('catalog::dashboard.products.validation.weight.required'),
            'vshipment.*.weight.numeric' => __('catalog::dashboard.products.validation.weight.numeric'),
            'vshipment.*.height.required' => __('catalog::dashboard.products.validation.height.required'),
            'vshipment.*.height.numeric' => __('catalog::dashboard.products.validation.height.numeric'),

            '_vshipment.*.width.required' => __('catalog::dashboard.products.validation.width.required'),
            '_vshipment.*.width.numeric' => __('catalog::dashboard.products.validation.width.numeric'),
            '_vshipment.*.length.required' => __('catalog::dashboard.products.validation.length.required'),
            '_vshipment.*.length.numeric' => __('catalog::dashboard.products.validation.length.numeric'),
            '_vshipment.*.weight.required' => __('catalog::dashboard.products.validation.weight.required'),
            '_vshipment.*.weight.numeric' => __('catalog::dashboard.products.validation.weight.numeric'),
            '_vshipment.*.height.required' => __('catalog::dashboard.products.validation.height.required'),
            '_vshipment.*.height.numeric' => __('catalog::dashboard.products.validation.height.numeric'),

            'image.required' => __('catalog::dashboard.products.validation.image.required'),
            'image.image' => __('catalog::dashboard.products.validation.image.image'),
            'image.mimes' => __('catalog::dashboard.products.validation.image.mimes'),
            'image.max' => __('catalog::dashboard.products.validation.image.max'),

            'v_images.*.image' => __('catalog::dashboard.products.validation.image.image'),
            'v_images.*.mimes' => __('catalog::dashboard.products.validation.image.mimes'),
            'v_images.*.max' => __('catalog::dashboard.products.validation.image.max'),
            '_v_images.*.image' => __('catalog::dashboard.products.validation.image.image'),
            '_v_images.*.mimes' => __('catalog::dashboard.products.validation.image.mimes'),
            '_v_images.*.max' => __('catalog::dashboard.products.validation.image.max'),

            'tags.array' => __('catalog::dashboard.products.validation.tags.array'),
            'search_keywords.array' => __('catalog::dashboard.products.validation.search_keywords.array'),
        ];

        foreach (config('laravellocalization.supportedLocales') as $key => $value) {
            $v['title.' . $key . '.required'] = __('catalog::dashboard.products.validation.title.required') . ' - ' . $value['native'] . '';
            $v["title." . $key . ".unique"] = __('catalog::dashboard.products.validation.title.unique') . ' - ' . $value['native'] . '';
        }

        if (isset($this->images) && !empty($this->images)) {
            foreach ($this->images as $k => $img) {
                $v['images.' . $k . '.mimes'] = __('catalog::dashboard.products.validation.images.mimes');
                $v['images.' . $k . '.max'] = __('catalog::dashboard.products.validation.images.max');
            }
        }

        return $v;
    }
}
