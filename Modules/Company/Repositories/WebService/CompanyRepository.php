<?php

namespace Modules\Company\Repositories\WebService;

use Modules\Company\Entities\Company;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\DeliveryCharge;
use Modules\Core\Traits\SyncRelationModel;

class CompanyRepository
{
    use SyncRelationModel;

    protected $company;
    protected $deliveryCharge;

    function __construct(Company $company, DeliveryCharge $deliveryCharge)
    {
        $this->company = $company;
        $this->deliveryCharge = $deliveryCharge;
    }

    public function findByIdAndStateId($stateId, $id)
    {
        return $this->company->with(['deliveryCharge' => function ($query) use ($stateId) {
            $query->where('state_id', $stateId);
        }, 'availabilities'])->find($id);
    }

    public function getDeliveryPrice($stateId, $companyId)
    {
        return $this->deliveryCharge::where('state_id', $stateId)
            ->where('company_id', $companyId)
            ->value('delivery');
    }

}
