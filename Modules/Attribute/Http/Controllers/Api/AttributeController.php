<?php

namespace Modules\Attribute\Http\Controllers\Api;

use Illuminate\Http\Request;

use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Attribute\Transformers\Api\AttributeResource;
use Modules\Attribute\Repositories\Api\AttributeRepository as Repo;


class AttributeController extends WebServiceController
{
    protected $repo;

    function __construct(Repo $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $attributes = $this->repo->getAll($request);
        return $this->response(
            AttributeResource::collection($attributes)
        );
    }

    public function getById(Request $request)
    {
        $attributes = $this->repo->getById($request);
        return $this->response(AttributeResource::collection($attributes));
    }

}
