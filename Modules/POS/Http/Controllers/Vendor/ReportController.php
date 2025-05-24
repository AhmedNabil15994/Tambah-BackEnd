<?php

namespace Modules\POS\Http\Controllers\Vendor;

use Modules\POS\Http\Controllers\Main\ReportController as MainReportController;
use Modules\POS\Repositories\Vendor\ReportRepository as Repo;




class ReportController extends MainReportController
{

    public function __construct(Repo $repo)
    {
        $this->repo = $repo;
        $this->path = 'pos::vendor.reports';
    }
}
