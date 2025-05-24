<?php

namespace Modules\Setting\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Area\Entities\Country;
use Modules\Area\Repositories\Dashboard\CountryRepository;
use Modules\Core\Traits\CoreTrait;
use Modules\Order\Repositories\Dashboard\OrderStatusRepository;
use Modules\Setting\Http\Requests\Dashboard\SettingRequest;
use Modules\Setting\Repositories\Dashboard\SettingRepository as Setting;

class SettingController extends Controller
{
    use CoreTrait;

    protected $setting;
    protected $country;
    protected $orderStatus;

    public function __construct(Setting $setting, CountryRepository $country, OrderStatusRepository $orderStatus)
    {
        $this->setting = $setting;
        $this->country = $country;
        $this->orderStatus = $orderStatus;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('setting::dashboard.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     */
    public function update(SettingRequest $request)
    {
        DB::beginTransaction();

        try {

            /* *********** Start Uploading Images Of App Gallery ************ */
            $configAppGallery = config('setting.app_gallery') ?? [];
            $configPaymentMethod = \Setting::get('payment_icons') ?? [];
            $res = $this->syncAppGallery($configAppGallery, $request->hidden_app_gallery ?? [], $request->app_gallery ?? []);
            $res['created'] = array_values(array_diff(array_keys($request->app_gallery ?? []), $res['updated']));
            $appGallery = [];
            if (!empty($request->app_gallery)) {
                if (!empty($res['created'])) {
                    foreach ($res['created'] as $key => $item) {
                        $imgName = $this->uploadImage(public_path('uploads/app_gallery'), $request->app_gallery[$item]);
                        $appGallery[$item] = $imgName;
                    }
                }

                if (!empty($res['updated'])) {
                    foreach ($res['updated'] as $key => $item) {
                        File::delete('uploads/app_gallery/' . $configAppGallery[$item]); ### Delete old image
                        $imgName = $this->uploadImage(public_path('uploads/app_gallery'), $request->app_gallery[$item]);
                        $appGallery[$item] = $imgName;
                        unset($configAppGallery[$item]);
                    }
                }

                if (!empty($res['deleted'])) {
                    foreach ($res['deleted'] as $key => $item) {
                        File::delete('uploads/app_gallery/' . $configAppGallery[$item]); ### Delete old image
                        unset($configAppGallery[$item]);
                    }
                }

                $appGallery = $appGallery + $configAppGallery; // merge arrays
            }
            /* *********** End Uploading Images Of App Gallery ************ */

            foreach ($request->payment_gateway as $key => $gateway) {

                if (!isset($gateway['status'])) {
                    $gateway['status'] = 'off';
                }

                $payment_gateway[$key] = $gateway;
            }
            $paymentMethod = $request->payment_method;
            foreach ($paymentMethod as $key => $payment) {
                $imgName = null;
                if (in_array($key, ['cashIcon', 'upaymentIcon', 'tapIcon', 'my_fatoorahIcon'])) {
                    if ($payment) {
                        if (isset($configPaymentMethod[$key])) {
                            File::delete( $configPaymentMethod[$key]); ### Delete old image
                        }
                        $imgName = $this->uploadImage(public_path('uploads/payment_method'), $paymentMethod[$key]);
                        unset($paymentMethod[$key]);
                    }
                    $paymentMethod[$key] = $imgName ? '/uploads/payment_method/' . $imgName : (isset($configPaymentMethod[$key]) ? $configPaymentMethod[$key] : null);
                }
            }

            $paymentsIcons = ['cashIcon', 'upaymentIcon', 'tapIcon', 'my_fatoorahIcon'];
            foreach ($paymentsIcons as $icon) {
                if (!isset($paymentMethod[$icon])) {
                    $paymentMethod[$icon] = isset($configPaymentMethod[$icon]) ? $configPaymentMethod[$icon] : '';
                }
            }

            $request->request->remove('payment_method');
            $request->request->set('payment_icons', $paymentMethod);
            $request->merge([
                'payment_gateway' => $payment_gateway
            ]);

            ### Start - Update Order Status In Model ###
            if ($request->order_status) {
                $this->orderStatus->updateColorInSettings($request->order_status);
            }
            ### End - Update Order Status In Model ###

            $request->request->remove('hidden_app_gallery');
            $this->setting->set($request);
            if (!empty($request->app_gallery)) {
                \Setting::set('app_gallery', $appGallery); // save custom app gallery
            }

            DB::commit();
            return redirect()->back()->with(['msg' => __('setting::dashboard.settings.form.messages.settings_updated_successfully'), 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function syncRelation($model, $incomingValues = null)
    {
        $oldIds = $model->pluck('code')->toArray();
        $data['deleted'] = array_values(array_diff($oldIds, $incomingValues));
        $data['updated'] = array_values(array_intersect($oldIds, $incomingValues));
        return $data;
    }

    public function syncAppGallery($settingData, $hiddenInputs, $incomingValues)
    {
        $data['deleted'] = array_values(array_diff(array_keys($settingData), array_keys($hiddenInputs)));
        $data['updated'] = array_values(array_intersect(array_keys($settingData), array_keys($incomingValues)));
        return $data;
    }
}
