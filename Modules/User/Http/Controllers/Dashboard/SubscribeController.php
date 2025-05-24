<?php

namespace Modules\User\Http\Controllers\Dashboard;

use Illuminate\Routing\Controller;
use Modules\Core\Traits\Dashboard\CrudDashboardController;

class SubscribeController extends Controller
{
    use CrudDashboardController{
        __construct as CrudConstruct;
    }

    public function __construct()
    {
        $this->CrudConstruct();
        $this->setViewPath('user::dashboard.subscreptions');
    }
}
