<?php

namespace Modules\POS\Http\Controllers\Dashboard;

use Modules\POS\Http\Controllers\Main\ReportController as MainReportController;
use Modules\POS\Repositories\Dashboard\ReportRepository as Repo;




class ReportController extends MainReportController
{

    public function __construct(Repo $repo)
    {
        $this->repo = $repo;
        $this->path = 'pos::dashboard.reports';
    }
}
