<?php

namespace Modules\Wrapping\Http\Controllers\WebService;

use Illuminate\Http\Request;

use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Catalog\Repositories\WebService\CatalogRepository as Product;
use Modules\Wrapping\Http\Requests\WebService\CardRequest;
use Modules\Wrapping\Repositories\WebService\WrappingRepository as Wrapping;
use Modules\Wrapping\Transformers\WebService\GiftResource;
use Modules\Wrapping\Transformers\WebService\CardResource;
use Modules\Wrapping\Transformers\WebService\AddonsResource;

class WrappingController extends WebServiceController
{
    protected $wrap;
    protected $product;

    function __construct(Wrapping $wrap, Product $product)
    {
        $this->wrap = $wrap;
        $this->product = $product;
    }

    public function index(Request $request)
    {
        // Get Gifts Data
        $gifts = $this->wrap->getAllActiveGifts();
        $result['gifts'] = GiftResource::collection($gifts);

       /*  // Get Cards Data
        $cards = $this->wrap->getAllActiveCards();
        $result['cards'] = CardResource::collection($cards); */

        // Get Wrapping Addons
        $addons = $this->wrap->getAllActiveAddons();
        $result['addons'] = AddonsResource::collection($addons);

        return $this->response($result);
    }

    public function wrapProductsWithGift(Request $request)
    {
        $products = isset($request->products) && !empty($request->products) ? array_values($request->products) : [];

        $gift = $this->wrap->findGiftById($request->gift_id);
        $allWidth = 0;
        $allLength = 0;
        $allHeight = 0;
        $allWeight = 0;

        if ($gift && $gift->qty < 1) {
            return $this->error(__('wrapping::webservice.gifts.quantity_not_available'), [], 401);
        }

        if (isset($products) && !empty($products)) {
            foreach ($products as $k => $product) {

                if ($product['type'] == 'variation') {
                    $prd = $this->product->findOneProductVariant($product['id']);
                } else {
                    $prd = $this->product->findOneProduct($product['id']);
                }

                if ($gift && $prd) {

                    $allWidth += floatval($prd->shipment['width']) * intval($product['quantity']);
                    $allLength += floatval($prd->shipment['length']) * intval($product['quantity']);
                    $allHeight += floatval($prd->shipment['height']) * intval($product['quantity']);
                    // $allWeight += floatval($prd->shipment['weight']) * intval($product['quantity']);

                    $check = $gift->size['width'] >= $allWidth && $gift->size['length'] >= $allLength && $gift->size['height'] >= $allHeight && $gift->size['weight'] >= $allWeight;
                    if ($check == false) {
                        if ($product['type'] == 'variation')
                            return $this->error(__('wrapping::webservice.gifts.size_not_suitable'), [], 401);
                        else
                            return $this->error(__('wrapping::webservice.gifts.size_not_suitable') . ': ' . $prd->title, [], 401);
                    }

                } else {
                    return $this->error(__('wrapping::webservice.gifts.this_product_not_exist'), [], 401);
                }
            }
        } else {
            return $this->error(__('wrapping::webservice.gifts.please_select_products'), [], 401);
        }

        return $this->response(true);
    }

    public function selectAddons(Request $request)
    {
        $addons = isset($request->addons) && !empty($request->addons) ? array_values($request->addons) : [];

        if (isset($addons) && !empty($addons)) {
            foreach ($addons as $k => $item) {

                $addonsObj = $this->wrap->findAddonsById($item['id']);
                if ($addonsObj) {

                    if (intval($item['quantity']) > intval($addonsObj->qty)) {
                        return $this->error(__('wrapping::webservice.addons.quantity_not_available') . ': ' . $addonsObj->title, [], 401);
                    }

                } else {
                    return $this->error(__('wrapping::webservice.addons.this_addons_not_exist'), [], 401);
                }
            }
        } else {
            return $this->error(__('wrapping::webservice.addons.please_select_addons'), [], 401);
        }

        return $this->response(true);
    }

    public function addCard(CardRequest $request, $id)
    {
        $card = $this->wrap->findCardById($id);
        if ($card) {

            // save cart in db

            return $this->response(true);

        } else {
            return $this->error(__('wrapping::webservice.cards.this_card_not_exist'), [], 401);
        }

    }

}
