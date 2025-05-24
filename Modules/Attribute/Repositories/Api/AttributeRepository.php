<?php

namespace Modules\Attribute\Repositories\Api;

use DB;
use Hash;
use Modules\Attribute\Entities\Attribute as Model;

class AttributeRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll(&$request, $order = 'id', $sort = 'asc')
    {
        $models = $this->model->active()
            ->when($request->show_in_search, function ($query) {
                $query->showInSearch();
            })
            ->when($request->category_id, function ($query) use (&$request) {
                $query->whereHas("categories", function ($category) use (&$request) {
                    $category->where("categories.id", $request->category_id);
                });
            })
            ->with(["optionsAllow"])
            ->orderBy($order, $sort)
            ->get();
        return $models;
    }

    public function getById(&$request)
    {
        $model = $this->model->active()
            ->when($request->show_in_search, function ($query) {
                $query->showInSearch();
            })
            ->with(["optionsAllow"])
            ->where('id', $request->id)
            ->get();
        return $model;
    }

    public function findById($id, $with = [])
    {
        $model = $this->model->active()
            ->showInSearch()->with($with)->findOrFail($id);
        return $model;
    }
}
