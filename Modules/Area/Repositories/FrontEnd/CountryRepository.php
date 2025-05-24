<?php

namespace Modules\Area\Repositories\FrontEnd;

use Modules\Area\Entities\Country;
use Setting;
use Hash;
use DB;

class CountryRepository
{
    protected $country;

    function __construct(Country $country)
    {
        $this->country = $country;
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        $query = $this->country->active();
        // Get only supported countries from settings
        $query = $query->whereIn('id', config('setting.supported_countries') ?? []);
        return $query->orderBy($order, $sort)->get();
    }

    public function getAllNameAndId($order = 'id', $sort = 'desc')
    {
        $query = $this->country->Active();
        // Get only supported countries from settings
        $query = $query->whereIn('id', 
        array_merge(
            config('setting.supported_countries') ?? [],
            array_merge(
                Setting::get('shiping.local.countries') ?? [],
                Setting::get('shiping.aramex.countries') ?? []
                )
        ));
        return $query->orderBy($order, $sort)->pluck('title','id')->toArray();
    }

    public function getCountriesWithCitiesAndStates($request = null, $order = 'id', $sort = 'asc')
    {
        $query = $this->country->Active()->with(['cities' => function ($q) {
            $q->Active();
            $q->with(['states' => function ($q) {
                $q->active();
            }]);
        }]);
        // Get only supported countries from settings
        $query = $query->whereIn('id', config('setting.supported_countries') ?? []);
        return $query->orderBy($order, $sort)->get();
    }

}
