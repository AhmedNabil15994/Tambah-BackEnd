<?php

namespace Modules\User\ViewComposers\Dashboard;

use Modules\User\Repositories\Dashboard\CashierRepository as User;
use Illuminate\View\View;
use Cache;

class CashierComposer
{
    public $user = [];

    public function __construct(User $user)
    {
        $this->user =  $user->getAllCashiers();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('cashiers' , $this->user);
    }
}
