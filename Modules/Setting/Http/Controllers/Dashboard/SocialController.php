<?php

namespace Modules\Setting\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Area\Entities\Country;
use Modules\Setting\Http\Requests\Dashboard\SettingRequest;
use Modules\Area\Repositories\Dashboard\CountryRepository;
use Modules\Order\Repositories\Dashboard\OrderStatusRepository;
use Modules\Setting\Repositories\Dashboard\SettingRepository as Setting;

class SocialController extends Controller
{
    protected $setting;
    protected $country;
    protected $orderStatus;

    function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('setting::dashboard.marketing.social.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     */
    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->setting->set($request);

            DB::commit();
            return redirect()->back()->with(['msg' => __('apps::dashboard.messages.updated'), 'alert' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
