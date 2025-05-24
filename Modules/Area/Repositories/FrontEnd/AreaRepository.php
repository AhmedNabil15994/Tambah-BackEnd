<?php

namespace Modules\Area\Repositories\FrontEnd;

use Modules\Area\Entities\City;
use Modules\Area\Entities\State;

class AreaRepository
{
    protected $city;
    protected $state;

    function __construct(City $city, State $state)
    {
        $this->city = $city;
        $this->state = $state;
    }

    public function getChildAreaByParent($request, $order = 'id', $sort = 'desc')
    {
        $query = null;
        if ($request->type == 'city') {
            $query = $this->city->active()->has('activeStates')->with(['states'])->where('country_id', $request->parent_id)->orderBy($order, $sort)->get();
        } elseif ($request->type == 'state') {
            $query = $this->state->active()->where('state_id', $request->parent_id)->has('activeDeliveryCharge')->orderBy($order, $sort)->get();
        }

        return $query;
    }


}
