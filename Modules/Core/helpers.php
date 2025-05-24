<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Area\Entities\CurrencyCode as CurrencyModel;
use Modules\Cart\Entities\DatabaseStorageModel;

// Active Dashboard Menu
if (!function_exists('active_menu')) {
    function active_menu($routeNames)
    {
        $routeNames = (array) $routeNames;
        return in_array(request()->segment(3), $routeNames) || in_array(request()->segment(4), $routeNames) ? 'active' : '';

        /*foreach ($routeNames as $routeName) {
    return (strpos(Route::currentRouteName(), $routeName) == 0) ? '' : 'active';
    }*/
    }
}

// GET THE CURRENT LOCALE
if (!function_exists('locale')) {

    function locale()
    {
        return app()->getLocale();
    }
}

if (!function_exists('ajaxSwitch')) {

    function ajaxSwitch($model, $url, $switch = 'status', $open = 1, $close = 0)
    {
        return view(
            'apps::dashboard.components.ajax-switch',
            compact('model', 'url', 'switch', 'open', 'close')
        )->render();
    }
}

if (!function_exists('active_slide_menu')) {
    function active_slide_menu($routeNames)
    {
        $response = [];
        foreach ((array) $routeNames as $name) {
            array_push($response, active_menu($name));
        }

        return in_array('active', $response) ? 'open' : '';
    }
}

// active header categories menu
if (!function_exists('activeCategoryTab')) {

    function activeCategoryTab($category, $index, $returnValue)
    {
        if (request()->has('category') && !empty(request()->get('category'))) {
            if (request()->get('category') == $category->slug) {
                return $returnValue;
            }
        } else {
            if ($index == 0) {
                return $returnValue;
            }
        }
        return is_bool($returnValue) === true ? false : '';
    }
}

// SAVE COOKIE with key and value
if (!function_exists('set_cookie_value')) {

    function set_cookie_value($key, $value, $expire = null)
    {
        $expire = $expire ?? time() + (2 * 365 * 24 * 60 * 60); // set a cookie that expires in 2 years
        setcookie($key, $value, $expire, '/');
        return true;
    }
}

// SAVE COOKIE with key and value
if (!function_exists('filterRouteWithQueryParams')) {

    function filterRouteWithQueryParams($route = null)
    {
        return count(request()->all()) || !$route ? request()->fullUrlWithQuery([]) : $route;
    }
}

// GET THE COOKIE value for Specific key
if (!function_exists('get_cookie_value')) {

    function get_cookie_value($key)
    {
        return isset($_COOKIE[$key]) && !empty($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }
}

// CHECK IF CURRENT LOCALE IS RTL
if (!function_exists('is_rtl')) {

    function is_rtl($locale = null)
    {

        $locale = ($locale == null) ? locale() : $locale;

        if (in_array($locale, config('rtl_locales'))) {
            return 'rtl';
        }

        return 'ltr';
    }
}

if (!function_exists('slugfy')) {
    /**
     * The Current dir
     *
     * @param  string  $locale
     */
    function slugfy($string, $separator = '-')
    {
        $url = trim($string);
        $url = strtolower($url);
        $url = preg_replace('|[^a-z-A-Z\p{Arabic}0-9 _]|iu', '', $url);
        $url = preg_replace('/\s+/', ' ', $url);
        $url = str_replace(' ', $separator, $url);

        return $url;
    }
}

if (!function_exists('priceWithCurrenciesCode')) {
    function priceWithCurrenciesCode($price, $price_return = true, $currency_code_return = true)
    {
        $code = defaultCurrency();

        if (
            session()->has('currency_data') &&
            isset(session()->get('currency_data')['selected_currency']) &&
            isset(session()->get('currency_data')['currencies_value'])
        ) {

            $price = number_format($price * session()->get('currency_data')['currencies_value'], 2);
            $code = session()->get('currency_data')['selected_currency']->code;
        }

        return ($price_return ? $price : '') . ($currency_code_return ? $code : '');
    }
}

if (!function_exists('defaultCurrency')) {
    function defaultCurrency($parameter = 'code')
    {
        if (!session()->has('default_currency') || optional(session()->get('default_currency'))->id != Setting::get('default_currency')) {

            session()->put('default_currency', CurrencyModel::find(Setting::get('default_currency')));
        }

        return optional(session()->get('default_currency'))->$parameter ?? null;
    }
}

/* if (!function_exists('slugAr')) {
function slugAr($string, $separator = '-')
{
if (is_null($string)) {
return "";
}

// Remove spaces from the beginning and from the end of the string
$string = trim($string);

// Lower case everything
// using mb_strtolower() function is important for non-Latin UTF-8 string | more info: https://www.php.net/manual/en/function.mb-strtolower.php
$string = mb_strtolower($string, "UTF-8");;

// Make alphanumeric (removes all other characters)
// this makes the string safe especially when used as a part of a URL
// this keeps latin characters and arabic charactrs as well
$string = preg_replace("/[^a-z0-9_\s\-ءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]#u/", "", $string);

// Remove multiple dashes or whitespaces
$string = preg_replace("/[\s-]+/", " ", $string);
$string = preg_replace("/[\s_]/", $separator, $string);
return $string;
}
}
 */

if (!function_exists('slugAr')) {

    function slugAr($string, $separator = '-')
    {
        if (is_null($string)) {
            return "";
        }
        $string = trim($string);
        $string = mb_strtolower($string, "UTF-8");
        // '/' and/or '\' if found and not removed it will change the get request route
        $string = str_replace('/', $separator, $string);
        $string = str_replace('\\', $separator, $string);
        $string = preg_replace("/[^a-z0-9_\sءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]/u", "", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $string = preg_replace("/[\s_]/", $separator, $string);
        $string = preg_replace("/[\s#]/", $separator, $string);
        return $string;
    }
}

if (!function_exists('path_without_domain')) {
    /**
     * Get Path Of File Without Domain URL
     *
     * @param  string  $locale
     */
    function path_without_domain($path)
    {
        $url = $path;
        $parts = explode("/", $url);
        array_shift($parts);
        array_shift($parts);
        array_shift($parts);
        $newurl = implode("/", $parts);

        return $newurl;
    }
}

if (!function_exists('int_to_array')) {
    /**
     * convert a comma separated string of numbers to an array
     *
     * @param  string  $integers
     */
    function int_to_array($integers)
    {
        return array_map("intval", explode(",", $integers));
    }
}

if (!function_exists('combinations')) {

    function combinations($arrays, $i = 0)
    {

        if (!isset($arrays[$i])) {
            return array();
        }

        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        // get combinations from subsequent arrays
        $tmp = combinations($arrays, $i + 1);

        $result = array();

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ?
                array_merge(array($v), $t) :
                array($v, $t);
            }
        }

        return $result;
    }
}

if (!function_exists('htmlView')) {
    /**
     * Access the OrderStatus helper.
     */
    function htmlView($content)
    {
        return
            '<!DOCTYPE html>
           <html lang="en">
             <head>
               <meta charset="utf-8">
               <meta http-equiv="X-UA-Compatible" content="IE=edge">
               <meta name="viewport" content="width=device-width, initial-scale=1">
               <link href="css/bootstrap.min.css" rel="stylesheet">
               <!--[if lt IE 9]>
                 <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
                 <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
               <![endif]-->
             </head>
             <body>
               ' . $content . '
               <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
               <script src="js/bootstrap.min.js"></script>
             </body>
           </html>';
    }
}

if (!function_exists('getDays')) {
    function getDays($dayCode = null)
    {
        if ($dayCode == null) {
            return [
                'sat' => __('company::dashboard.companies.availabilities.days.sat'),
                'sun' => __('company::dashboard.companies.availabilities.days.sun'),
                'mon' => __('company::dashboard.companies.availabilities.days.mon'),
                'tue' => __('company::dashboard.companies.availabilities.days.tue'),
                'wed' => __('company::dashboard.companies.availabilities.days.wed'),
                'thu' => __('company::dashboard.companies.availabilities.days.thu'),
                'fri' => __('company::dashboard.companies.availabilities.days.fri'),
            ];
        } else {
            switch ($dayCode) {
                case 'sat':
                    return __('company::dashboard.companies.availabilities.days.sat');
                    break;
                case 'sun':
                    return __('company::dashboard.companies.availabilities.days.sun');
                    break;
                case 'mon':
                    return __('company::dashboard.companies.availabilities.days.mon');
                case 'tue':
                    return __('company::dashboard.companies.availabilities.days.tue');
                    break;
                case 'wed':
                    return __('company::dashboard.companies.availabilities.days.wed');
                    break;
                case 'thu':
                    return __('company::dashboard.companies.availabilities.days.thu');
                    break;
                case 'fri':
                    return __('company::dashboard.companies.availabilities.days.fri');
                    break;
                default:
                    return 'not_exist';
            }
        }
    }
}

if (!function_exists('getFullDayByCode')) {
    function getFullDayByCode($dayCode)
    {
        switch ($dayCode) {
            case 'sat':
                return 'saturday';
                break;
            case 'sun':
                return 'sunday';
                break;
            case 'mon':
                return 'monday';
            case 'tue':
                return 'tuesday';
                break;
            case 'wed':
                return 'wednesday';
                break;
            case 'thu':
                return 'thursday';
                break;
            case 'fri':
                return 'friday';
                break;
            default:
                return null;
        }
    }
}

if (!function_exists('checkSelectedCartGiftProducts')) {
    function checkSelectedCartGiftProducts($prdId, $giftId)
    {
        $giftCondition = Cart::getCondition('gift');

        if ($giftCondition) {
            $giftsArray = $giftCondition->getAttributes()['wrapping'];

            foreach ($giftsArray as $key => $item) {
                if ($prdId == ($item['products'][0]['id'] ?? []) && $key == $giftId) {
                    return true;
                }
            }
        }

        return false;
    }
}

if (!function_exists('checkSelectedCartCards')) {
    function checkSelectedCartCards($cardId)
    {
        $condition = Cart::getCondition('card');

        if ($condition && isset($condition->getAttributes()['cards'][$cardId])) {
            return $condition->getAttributes()['cards'][$cardId];
        }

        return null;
    }
}

if (!function_exists('checkSelectedCartAddons')) {
    function checkSelectedCartAddons($addonsId)
    {
        $condition = Cart::getCondition('addons');

        if ($condition && isset($condition->getAttributes()['addons'][$addonsId])) {
            return $condition->getAttributes()['addons'][$addonsId];
        }

        return null;
    }
}

if (!function_exists('checkSelectedVendorDeliveryCompany')) {
    function checkSelectedVendorDeliveryCompany($vendorId, $companyId)
    {
        $condition = Cart::getCondition('company_delivery_fees');

        if ($condition && isset($condition->getAttributes()['vendors'][$vendorId][$companyId])) {
            return 'checked';
        }

        return null;
    }
}

if (!function_exists('getDayByDayCode')) {
    function getDayByDayCode($dayCode)
    {
        if (strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($dayCode)))) {
            return [
                'full_date' => date('Y-m-d', strtotime($dayCode)),
                'day' => date('d', strtotime($dayCode)),
            ];
        }

        return '';
    }
}

if (!function_exists('generateVariantProductData')) {
    function generateVariantProductData($product, $variantPrdId, $optionValues)
    {
        if (!empty($optionValues) && count($optionValues) > 0) {
            foreach (config('translatable.locales') as $code):
                $generatedName[$code] = $product->getTranslation('title', $code) . ' - ';
            endforeach;

            $generatedSlug = 'var=' . $variantPrdId . '&';
            foreach ($optionValues as $k => $value) {
                $optionValue = \Modules\Variation\Entities\OptionValue::with('option')->find($value);

                foreach (config('translatable.locales') as $code):
                    $generatedName[$code] .= $k == 0 ? optional($optionValue)->getTranslation('title', $code) : ', ' . optional($optionValue)->getTranslation('title', $code);
                endforeach;

                /* $valueSlug = Str::slug(optional($optionValue)->title);
                generatedSlug .= 'attr_' . Str::slug(Str::lower(optional(optional($optionValue)->option)->title)) . '=';
                $generatedSlug .= $k === array_key_last($optionValues) ? $valueSlug : $valueSlug . '&'; */

                $generatedSlug .= 'attr_' . $optionValue->option->id . '=';
                $generatedSlug .= $k === array_key_last($optionValues) ? $optionValue->id : $optionValue->id . '&';
            }
            return [
                'name' => $generatedName[locale()],
                'name_locales' => $generatedName,
                'slug' => $generatedSlug,
            ];
        } else {
            return [
                'name' => '',
                'name_locales' => [],
                'slug' => '',
            ];
        }
    }
}

if (!function_exists('getOptionQueryString')) {
    function getOptionQueryString($string)
    {
        $pieces = explode('_', $string);
        return $pieces[1];
    }
}

if (!function_exists('findProductById')) {
    function findProductById($id)
    {
        return Modules\Catalog\Repositories\FrontEnd\ProductRepository::findProductById($id);
    }
}

if (!function_exists('getOptionsAndValuesIds')) {
    function getOptionsAndValuesIds($request)
    {
        $selectedOptions = [];
        $selectedOptionsValue = [];

        foreach ($request->query() as $k => $query) {
            if (Str::startsWith($k, 'attr_')) {
                $optionId = getOptionQueryString($k);
                $option = \Modules\Variation\Entities\Option::active()->find($optionId);
                $selectedOptions[] = $option ? $option->id : "";
                $optionValId = $query;
                $optionVal = \Modules\Variation\Entities\OptionValue::active()->where(
                    'option_id',
                    $option ? $option->id : 0
                )->find($optionValId);
                $selectedOptionsValue[] = $optionVal ? $optionVal->id : "";
            }
        }

        return [
            'selectedOptions' => $selectedOptions,
            'selectedOptionsValue' => $selectedOptionsValue,
        ];
    }
}

if (!function_exists('generateRandomCode')) {
    function generateRandomCode($length = 8)
    {
        return Str::upper(Str::random($length));
    }
}

if (!function_exists('generateRandomNumericCode')) {
    function generateRandomNumericCode($length = 5)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('limitString')) {
    function limitString($string, $length = 50, $end = '...')
    {
        return Str::limit($string, $length, $end);
    }
}

if (!function_exists('toggleSideMenuItemsByVendorType')) {
    function toggleSideMenuItemsByVendorType()
    {
        return config('setting.other.is_multi_vendors') == 1 ? 'block' : 'none';
    }
}

if (!function_exists('getCartContent')) {
    function getCartContent($userToken = null, $force = false)
    {
        if (!$force && session()->has('CartContent')) {
            return session()->get('CartContent');
        }

        if (is_null($userToken)) {
            if (auth()->check()) {
                $userToken = auth()->user()->id ?? null;
            } else {
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
            }
        }

        if (!is_null($userToken)) {
            $query = Cart::session($userToken)->getContent();
        } else {
            $query = Cart::getContent();
        }

        $result = $query;
        $cartProductQuantity = $query->pluck('quantity', 'id');
        session()->flash('CartContent', $result);
        session()->flash('cartProductQuantity', $cartProductQuantity);
        return $result;
    }
}

if (!function_exists('getCartItemById')) {
    function getCartItemById($id, $userToken = null)
    {
        if (is_null($userToken)) {
            if (auth()->check()) {
                $userToken = auth()->user()->id ?? null;
            } else {
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
            }
        }

        if (!is_null($userToken)) {
            $result = Cart::session($userToken)->get($id);
        } else {
            $result = Cart::get($id);
        }

        return $result ?? null;
    }
}

if (!function_exists('getCartQuantityById')) {
    function getCartQuantityById($id, $userToken = null)
    {
        if (!session()->has('cartProductQuantity')) {
            getCartContent($userToken);
        }

        return session()->get('cartProductQuantity')[$id] ?? null;
    }
}

if (!function_exists('checkRouteLocale')) {
    function checkRouteLocale($model, $slug)
    {
        return $model->getTranslation("slug", locale()) == $slug;
    }
}

if (!function_exists('getCartTotal')) {
    function getCartTotal($userToken = null)
    {
        if (is_null($userToken)) {
            if (auth()->check()) {
                $userToken = auth()->user()->id ?? null;
            } else {
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
            }
        }

        if (!is_null($userToken)) {
            $result = Cart::session($userToken)->getTotal();
        } else {
            $result = Cart::getTotal();
        }

        return $result ?? null;
    }
}

if (!function_exists('getCartSubTotal')) {
    function getCartSubTotal($userToken = null)
    {
        if (is_null($userToken)) {
            if (auth()->check()) {
                $userToken = auth()->user()->id ?? null;
            } else {
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
            }
        }

        if (!is_null($userToken)) {
            $result = Cart::session($userToken)->getSubTotal();
        } else {
            $result = Cart::getSubTotal();
        }

        return $result ?? null;
    }
}

if (!function_exists('removeCartCondition')) {
    function removeCartCondition($conditionName, $userToken = null)
    {
        if (is_null($userToken)) {
            if (auth()->check()) {
                $userToken = auth()->user()->id ?? null;
            } else {
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
            }
        }

        if (!is_null($userToken)) {
            $result = Cart::session($userToken)->removeCartCondition($conditionName);
        } else {
            $result = Cart::removeCartCondition($conditionName);
        }

        return $result ?? null;
    }
}

if (!function_exists('getOrderShipping')) {
    function getOrderShipping($userToken = null)
    {
        if (is_null($userToken)) {
            if (auth()->check()) {
                $userToken = auth()->user()->id ?? null;
            } else {
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
            }
        }

        if (!request()->from_cashier) {
            if (!is_null($userToken)) {
                $result = optional(Cart::session($userToken)->getCondition('company_delivery_fees'))->getValue() ?? null;
            } else {
                $result = Cart::getCondition('company_delivery_fees')->getValue() ?? null;
            }
        }

        return $result ?? 0;
    }
}

if (!function_exists('getCartConditionByName')) {
    function getCartConditionByName($userToken = null, $name = '')
    {
        if (is_null($userToken)) {
            if (auth()->check()) {
                $userToken = auth()->user()->id ?? null;
            } else {
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
            }
        }

        if (!is_null($userToken)) {
            $result = Cart::session($userToken)->getCondition($name) ?? null;
        } else {
            $result = Cart::getCondition($name) ?? null;
        }

        return $result ?? null;
    }
}

if (!function_exists('addItemCondition')) {
    function addItemCondition($productId, $itemCondition, $userToken = null)
    {
        if (is_null($userToken)) {
            if (auth()->check()) {
                $userToken = auth()->user()->id ?? null;
            } else {
                $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
            }
        }

        if (!is_null($userToken)) {
            $result = Cart::session($userToken)->addItemCondition($productId, $itemCondition);
        } else {
            $result = Cart::addItemCondition($productId, $itemCondition);
        }

        return $result ?? null;
    }
}

if (!function_exists('getCartItemsCouponValue')) {
    function getCartItemsCouponValue($userToken = null)
    {
        $value = null;
        $items = getCartContent($userToken);
        if (!$items->isEmpty()) {
            foreach ($items as $item) {
                foreach ($item->getConditions() as $condition) {
                    if ($condition->getName() == 'product_coupon') {
                        $value += intval($item->quantity) * abs($condition->getValue());
                    }
                }
            }
        }
        return $value;
    }
}

if (!function_exists('getProductCartCount')) {
    function getProductCartCount($id)
    {
        $result = DatabaseStorageModel::where('id', 'LIKE', '%cart_items%')->get()->reject(function ($item) {
            return count($item->cart_data) == 0;
        })->map(function ($item) use ($id) {
            return $item->cart_data->each(function ($collection, $key) use ($id) {
                if ($key == $id) {
                    return $collection;
                }

                //                dump($collection->toArray(), 'key::' . $key, ':::id:::' . $id);
            });
        });

        return array_values($result->toArray());
    }
}

if (!function_exists('getProductCartNotes')) {
    function getProductCartNotes($product, $variantPrd = null)
    {
        $cartPrdId = !is_null($variantPrd) ? 'var-' . $variantPrd->id : $product->id;
        if (getCartItemById($cartPrdId)) {
            return getCartItemById($cartPrdId)->attributes['notes'] ?? '';
        } else {
            return '';
        }
    }
}

if (!function_exists('calculateOfferAmountByPercentage')) {
    function calculateOfferAmountByPercentage($productPrice, $offerPercentage)
    {
        $percentageResult = (floatval($offerPercentage) * floatval($productPrice)) / 100;
        return floatval($productPrice) - $percentageResult;
    }
}

if (!function_exists('calculateOfferPercentageByAmount')) {
    function calculateOfferPercentageByAmount($productPrice, $offerAmount)
    {
        return ((floatval($productPrice) - floatval($offerAmount)) / floatval($productPrice)) * 100;
    }
}

if (!function_exists('generateBarcodeNumber')) {
    function generateBarcodeNumber($table = null, $colum = "sku", $forceGenerate = false)
    {
        $number = $forceGenerate == true ? mt_rand(10000000, 9999999999) : mt_rand(1000000, 9999999); // better than rand()

        // call the same function if the barcode exists already
        if (barcodeNumberExists($number, $table, $colum)) {
            return generateBarcodeNumber($table, $colum, $forceGenerate);
        }

        // otherwise, it's valid and can be used
        return $number;
    }
}

if (!function_exists('barcodeNumberExists')) {
    function barcodeNumberExists($number, $table, $colum)
    {
        // query the database and return a boolean
        // for instance, it might look like this in Laravel
        return DB::table($table)->where($colum, $number)->exists();
    }
}

if (!function_exists('checkShipping')) {
    function checkShipping($model)
    {
        return $model->shipment && ($model->weight || $model->width || $model->length || $model->height);
    }
}

if (!function_exists('is_new_row')) {
    function is_new_row($barcode_details, $loop_count)
    {
        return ($barcode_details->stickers_in_one_row == 1 || ($loop_count % $barcode_details->stickers_in_one_row) == 1) ? true : false;
    }
}

if (!function_exists('is_end_row')) {
    function is_end_row($barcode_details, $loop_count)
    {
        return ($barcode_details->stickers_in_one_row == 1 || ($loop_count % $barcode_details->stickers_in_one_row) == 0) ? true : false;
    }
}

if (!function_exists('is_new_paper')) {
    function is_new_paper($barcode_details, $loop_count)
    {
        if ($barcode_details->is_continuous) {
            return $loop_count == 1;
        }
        return (
            //  ( $barcode_details->is_continuous && is_new_row($barcode_details, $loop_count) ) ||
            ($barcode_details->stickers_in_one_sheet && ($loop_count % $barcode_details->stickers_in_one_sheet == 1 || $barcode_details->stickers_in_one_sheet == 1)) ||
            (!$barcode_details->is_continuous && $barcode_details->stickers_in_one_sheet && ($loop_count % $barcode_details->stickers_in_one_sheet == 1 || $barcode_details->stickers_in_one_sheet == 1))
        );
    }
}

if (!function_exists('is_paper_end')) {
    function is_paper_end($barcode_details, $loop_count)
    {
        if ($barcode_details->is_continuous) {
            return false;
        }
        return (
            //  ($barcode_details->is_continuous && ($loop_count % $barcode_details->stickers_in_one_row == 0))  ||
            ($barcode_details->is_continuous && $barcode_details->stickers_in_one_sheet && ($loop_count % $barcode_details->stickers_in_one_sheet == 0 || $barcode_details->stickers_in_one_sheet == 1))
            || (!$barcode_details->is_continuous && ($loop_count % $barcode_details->stickers_in_one_sheet == 0))
        );
    }
}

if (!function_exists('is_first_row')) {
    function is_first_row($barcode_details, $loop_count)
    {
        return ((!$barcode_details->is_continuous) && ($loop_count % $barcode_details->stickers_in_one_sheet) <= $barcode_details->stickers_in_one_row) ||
            ($barcode_details->is_continuous && ($loop_count <= $barcode_details->stickers_in_one_row));
    }
}

if (!function_exists('getOrderAddressDetails')) {
    function getOrderAddressDetails($order)
    {
        $response = [];
        $name = '';
        $mobile = '';
        $shippingAddress = '';

        if (!is_null($order->orderAddress)) {
            $response['state'] = optional($order->orderAddress->state)->title;
            $name = $order->orderAddress->username;
            $mobile = $order->orderAddress->mobile;
            $shippingAddress = buildAddressInfo($order->orderAddress);
        } elseif (!is_null($order->unknownOrderAddress)) {
            $response['state'] = optional($order->unknownOrderAddress->state)->title;
        } else {
            $response['state'] = null;
        }

        if (is_null($name)) {
            $name = $order->user ? $order->user->name : null;
        }

        if (is_null($mobile)) {
            $mobile = $order->user ? $order->user->mobile : null;
        }

        $response['name'] = $name;
        $response['mobile'] = $mobile;
        $response['shipping_address'] = $shippingAddress;
        return $response;
    }
}

if (!function_exists('buildAddressInfo')) {
    function buildAddressInfo($address)
    {
        $addressData = '';
        if ($address->state) {
            $addressData .= __('order::dashboard.orders.address_data.state') . ': ' . $address->state->title;
        }
        if ($address->street) {
            $addressData .= ' / ' . __('order::dashboard.orders.address_data.street') . ': ' . $address->street;
        }
        if ($address->block) {
            $addressData .= ' / ' . __('order::dashboard.orders.address_data.block') . ': ' . $address->block;
        }
        if ($address->building) {
            $addressData .= ' / ' . __('order::dashboard.orders.address_data.building') . ': ' . $address->building;
        }
        if ($address->avenue) {
            $addressData .= ' / ' . __('order::dashboard.orders.address_data.avenue') . ': ' . $address->avenue;
        }
        if ($address->floor) {
            $addressData .= ' / ' . __('order::dashboard.orders.address_data.floor') . ': ' . $address->floor;
        }
        if ($address->flat) {
            $addressData .= ' / ' . __('order::dashboard.orders.address_data.flat') . ': ' . $address->flat;
        }
        if ($address->address) {
            $addressData .= ' / ' . __('order::dashboard.orders.address_data.address_details') . ': ' . $address->address;
        }
        return $addressData;
    }
}