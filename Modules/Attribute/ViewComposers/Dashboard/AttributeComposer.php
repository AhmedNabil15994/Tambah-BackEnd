<?php

namespace Modules\Attribute\ViewComposers\Dashboard;

use Modules\Attribute\Repositories\Dashboard\AttributeRepository as Page;
use Illuminate\View\View;


class AttributeComposer
{
    public $attributes = [];

    public function __construct(Page $page)
    {

        $this->attributes = $page->getAllActive();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('attributes', $this->attributes);
    }
}
