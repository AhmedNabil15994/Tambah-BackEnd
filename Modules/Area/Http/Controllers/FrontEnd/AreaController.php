<?php

namespace Modules\Area\Http\Controllers\FrontEnd;

use ExtremeSa\Aramex\Facades\Aramex;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Area\Entities\Country;
use Modules\Area\Repositories\FrontEnd\AreaRepository as Area;
use Modules\Area\Transformers\FrontEnd\AreaSelectorResource;
use Setting;

class AreaController extends Controller
{
    protected $area;

    public function __construct(Area $area)
    {
        $this->area = $area;
    }

    public function getChildAreaByParent(Request $request)
    {
        $country = null;
        $items = [];

        if ($request->type == 'city') {

            $country = Country::active()->findOrFail($request->parent_id);
            if (in_array($country->id, Setting::get('shiping.aramex.countries', []))) {

                $data = Aramex::fetchCities()->setCountryCode($country->iso2)->run();

                foreach ($data->getCities() as $id => $title) {
                    array_push($items, [
                        'id' => $title,
                        'title' => $title,
                    ]);
                }

                $items = [(object) [
                    'id' => 1,
                    'title' => $country->title,
                    'states' => (object) $items,
                ]];
            } else {

                $items = AreaSelectorResource::collection($this->area->getChildAreaByParent($request));
            }
        }

        return response()->json(['success' => true, 'data' => $items, 'country' => $country->iso2]);
    }
}
