<?php

namespace Modules\Cart\Http\Controllers\WebService\V2;

use Cart;
use Illuminate\Http\Request;
use Modules\Cart\Traits\CartTrait;
use Modules\Cart\Traits\CartReplaceTrait;
use Modules\POS\Transformers\POS\ProductResource;
use Modules\POS\Transformers\POS\ProductVariantResource;
use Modules\Cart\Transformers\WebService\CartResource;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Cart\Http\Requests\Api\CompanyDeliveryFeesConditionRequest;
// use Modules\Catalog\Entities\ProductAddon;
use Modules\Catalog\Repositories\WebService\V2\CatalogRepository as Product;
use Modules\Catalog\Entities\Product as ProductModel;
use Modules\User\Repositories\WebService\AddressRepository as AddressRepo;
use Modules\Company\Repositories\WebService\CompanyRepository as CompanyRepo;

class CartController extends WebServiceController
{
    use CartTrait,
        CartReplaceTrait;

    protected $product;
    protected $company;
    protected $userAddress;

    public function __construct(Product $product, CompanyRepo $company, AddressRepo $userAddress)
    {
        $this->product = $product;
        $this->company = $company;
        $this->userAddress = $userAddress;
    }

    public function index(Request $request)
    {
        return $this->response($this->responseData($request));
    }

    public function handleDraft(Request $request)
    {
        $currrent = $this->getCurrentCartResponse($request) ;
        $this->clearCart($request['user_token'])   ;
        return $this->response($currrent);
    }

    public function handleCartReplace(Request $request)
    {
        $old = $this->getCurrentCartResponse($request);
        $this->replaceCart($request);
        return $this->response(["old"=>$old, "new"=>$this->responseData($request)]);
    }

    public function updatePriceItem(Request $request)
    {
        $item = $this->handleUpdatePrice($request);
        return $this->response($this->responseData($request));
    }

    public function createOrUpdate(Request $request)
    {
        if (is_null($request->user_token)) {
            return $this->error(__('apps::frontend.general.user_token_not_found'), [], 422);
        }

        // check if product single OR variable (variant)
        if ($request->product_type == 'product') {
            $product = $this->product->findOneProduct($request->product_id);
            if (!$product) {
                return $this->error(__('cart::api.cart.product.not_found') . $request->product_id, [], 422);
            }

            $product->product_type = 'product';
        } else {
            $product = $this->product->findOneProductVariant($request->product_id);
            if (!$product) {
                return $this->error(__('cart::api.cart.product.not_found') . $request->product_id, [], 422);
            }

            $product->product_type = 'variation';

            // Get variant product options and values
            $options = [];
            foreach ($product->productValues as $k => $value) {
                $options[] = $value->productOption->option->id;
            }
            $selectedOptionsValue = $product->productValues->pluck('option_value_id')->toArray();

            // Append options and options values to current request
            // - encode data to match frontend scenario
            $request->request->add([
                'selectedOptions' => json_encode($options),
                'selectedOptionsValue' => json_encode($selectedOptionsValue),
            ]);
        }

        $res = $this->addOrUpdateCart($product, $request);

        if (gettype($res) == 'string') {
            return $this->error($res, [], 422);
        }

        return $this->response($this->responseData($request));
    }

    public function createOrUpdateFromSku(Request $request)
    {
        $product = $this->product->findOneProductSky($request->sku);

        // check if product single OR variable (variant)
        if ($product) {
            $product->product_type = 'product';
        } else {
            $product = $this->product->findOneProductVariantSku($request->sku);
            if (!$product) {
                return $this->error(__('cart::api.cart.product.not_found') . $request->product_id, [], 422);
            }

            $product->product_type = 'variation';


            // Get variant product options and values
            $options = [];
            foreach ($product->productValues as $k => $value) {
                $options[] = $value->productOption->option->id;
            }
            $selectedOptionsValue = $product->productValues->pluck('option_value_id')->toArray();

            // Append options and options values to current request
            // - encode data to match frontend scenario
            $request->request->add([
                'selectedOptions' => json_encode($options),
                'selectedOptionsValue' => json_encode($selectedOptionsValue),
            ]);

            /*if (!isset($request->selectedOptions) || empty($request->selectedOptions)) {
                $error = 'Please, Enter Selected Options';
                return $this->error($error, [], 422);
            }

            if (!isset($request->selectedOptionsValue) || empty($request->selectedOptionsValue)) {
                $error = 'Please, Enter Selected Options Values';
                return $this->error($error, [], 422);
            }*/
        }

        if($product instanceof ProductModel){
            $res = new ProductResource($product);
        }else{
            $res = new ProductVariantResource($product);
        }

        return $this->response($res);
    }

    public function remove(Request $request, $id)
    {
        $this->removeItem($request, $id);
        return $this->response($this->responseData($request));
    }

    public function addCompanyDeliveryFeesCondition(CompanyDeliveryFeesConditionRequest $request)
    {
        /*if (getCartSubTotal($request->user_token) <= 0)
            return $this->error(__('coupon::api.coupons.validation.cart_is_empty'), [], 422);*/

        if (auth('api')->check()) {
            // Get user address and state by address_id
            $address = $this->userAddress->findById($request->address_id);
            if (!$address) {
                return $this->error(__('user::webservice.address.errors.address_not_found'));
            }

            $request->request->add(['state_id' => $address->state_id]);
        }

        $companyId = config('setting.other.shipping_company') ?? 0;
        $price = $this->company->getDeliveryPrice($request->state_id, $companyId);

        if ($price) {
            $this->removeConditionByName($request, 'company_delivery_fees');
            $this->companyDeliveryChargeCondition($request, floatval($price));
        } else {
            $this->removeConditionByName($request, 'company_delivery_fees');
            return $this->error(__('catalog::frontend.checkout.validation.state_not_supported_by_company'), [], 422);
        }

        $result = [
            'conditions' => $this->getCartConditions($request),
            'subTotal' => $this->cartSubTotal($request),
            'total' => $this->cartTotal($request),
            'count' => $this->cartCount($request),
        ];
        return $this->response($result);
    }

    public function removeCondition(Request $request, $name)
    {
        $this->removeConditionByName($request, $name);
        return $this->response($this->responseData($request));
    }

    public function clear(Request $request)
    {
        $this->clearCart($request->user_token);
        return $this->response($this->responseData($request));
    }

    public function responseData($request)
    {
        $collections = collect($this->cartDetails($request));
        return [
            'items' => CartResource::collection($collections),
            'conditions' => $this->getCartConditions($request),
            'subTotal' => number_format($this->cartSubTotal($request),3),
            'total' => number_format($this->cartTotal($request),3),
            'count' => number_format($this->cartCount($request),3),
        ];
    }

    /* public function addonsValidation($request, $productId)
    {
        $request->addonsOptions = isset($request->addonsOptions) ? json_decode($request->addonsOptions) : [];
        if (isset($request->addonsOptions) && !empty($request->addonsOptions) && $request->product_type == 'product') {
            foreach ($request->addonsOptions as $k => $value) {

                $addOns = ProductAddon::where('product_id', $productId)->where('addon_category_id', $value->id)->first();
                if (!$addOns) {
                    return __('cart::api.validations.addons.addons_not_found') . ' - ' . __('cart::api.validations.addons.addons_number') . ': ' . $value->id;
                }

                $optionsIds = $addOns->addonOptions ? $addOns->addonOptions->pluck('addon_option_id')->toArray() : [];
                if ($addOns->type == 'single' && count($value->options) > 0 && !in_array($value->options[0], $optionsIds)) {
                    return __('cart::api.validations.addons.option_not_found') . ' - ' . __('cart::api.validations.addons.addons_number') . ': ' . $value->options[0];
                }

                if ($addOns->type == 'multi') {
                    if ($addOns->max_options_count != null && count($value->options) > intval($addOns->max_options_count)) {
                        return __('cart::api.validations.addons.selected_options_greater_than_options_count') . ': ' . $addOns->addonCategory->getTranslation('title', locale());
                    }

                    if ($addOns->min_options_count != null && count($value->options) < intval($addOns->min_options_count)) {
                        return __('cart::api.validations.addons.selected_options_less_than_options_count') . ': ' . $addOns->addonCategory->getTranslation('title', locale());
                    }

                    if (count($value->options) > 0) {
                        foreach ($value->options as $i => $item) {
                            if (!in_array($item, $optionsIds)) {
                                return __('cart::api.validations.addons.option_not_found') . ' - ' . __('cart::api.validations.addons.addons_number') . ': ' . $item;
                            }
                        }
                    }
                }
            }
        }

        return true;
    } */
}
