<?php

namespace Modules\Slider\Repositories\Dashboard;

use Illuminate\Http\Request;
use Modules\Core\Repositories\Dashboard\CrudRepository;
use Modules\Slider\Entities\Instagram;

class InstagramRepository extends CrudRepository
{
    public function __construct($model = null)
    {
        parent::__construct(new Instagram);

        $this->fileAttribute = ['image' => 'image'];
    }

    public function prepareData(array $data, Request $request, $model, $is_create = true): array
    {

        $data['status'] = $request->status ? 1 : 0;
        return parent::prepareData($data, $request, $model, $is_create);
    }
}
